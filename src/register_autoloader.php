<?php

$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
$caller_file = $backtrace[0]['file'] ?? null;

if (!$caller_file) {
    return;
}

$psr4_path = dirname($caller_file) . '/vendor/composer/autoload_psr4.php';

if (!file_exists($psr4_path)) {
    return;
}

$prefixes = include $psr4_path;

// 無名関数（衝突を防ぐ）
$extract_textdomain = function ($namespace) {
    $parts = explode('\\', trim($namespace, '\\'));
    if (count($parts) >= 2) {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $parts[1]));
    }
    return null;
};

// 翻訳ファイルの自動ロード処理
foreach ($prefixes as $prefix => $dirs) {
    foreach ((array) $dirs as $dir) {
        $textdomain = $extract_textdomain($prefix);
        $base_path = dirname($dir);
        $languages_path = $base_path . '/languages';

        if ($textdomain && is_dir($languages_path)) {
            $locale = determine_locale();
            $mofile = $languages_path . '/' . $textdomain . '-' . $locale . '.mo';
            if (file_exists($mofile)) {
                load_textdomain($textdomain, $mofile);
            }
        }
    }
}

// オートローダー登録
spl_autoload_register(function ($class) use ($prefixes) {
    foreach ($prefixes as $prefix => $dirs) {
        if (strpos($class, $prefix) === 0) {
            $relative_class = substr($class, strlen($prefix));
            $relative_path = ltrim(str_replace('\\', DIRECTORY_SEPARATOR, $relative_class), DIRECTORY_SEPARATOR);
            foreach ((array) $dirs as $baseDir) {
                $file = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR . $relative_path . '.php';
                if (file_exists($file)) {
                    require $file;
                    return true;
                }
            }
        }
    }
    return false;
}, true, true);
