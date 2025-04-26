<?php

namespace Itmar\Autoloader;

class Loader
{
    protected static $prefixes = [];

    public static function register($psr4_path = null)
    {
        if (!self::$prefixes) {
            if (!$psr4_path) {
                throw new \InvalidArgumentException('Path to autoload_psr4.php is required.');
            }
            self::$prefixes = require $psr4_path;
        }

        spl_autoload_register([__CLASS__, 'autoload']);

        // 各パッケージの言語ファイルを自動ロード
        foreach (self::$prefixes as $prefix => $dirs) {
            foreach ($dirs as $dir) {
                $textdomain = self::extract_textdomain_from_namespace($prefix);
                $base_path  = dirname($dir);
                $languages_path = $base_path . '/languages';
                if ($textdomain && is_dir($languages_path)) {
                    // コンポーネントのディレクトリを取得
                    $locale = determine_locale();
                    $mofile = $languages_path . '/' . $textdomain . '-' . $locale . '.mo';

                    if (file_exists($mofile)) {
                        // テキストドメインを直接ロードする（フィルターに依存しない）
                        load_textdomain($textdomain, $mofile);
                    }
                }
            }
        }
    }

    public static function autoload($class)
    {
        foreach (self::$prefixes as $prefix => $dirs) {
            if (strpos($class, $prefix) === 0) {
                foreach ($dirs as $dir) {
                    $relative_class = substr($class, strlen($prefix));
                    $file = rtrim($dir, '/\\') . '/' . str_replace('\\', '/', $relative_class) . '.php';
                    if (file_exists($file)) {
                        require $file;
                        return;
                    }
                }
            }
        }
    }

    protected static function extract_textdomain_from_namespace($namespace)
    {
        // 例: Itmar\\BlockClassPackage\\ → block-class-package
        $parts = explode('\\', trim($namespace, '\\'));
        if (count($parts) >= 2) {
            return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $parts[1]));
        }
        return null;
    }
}
