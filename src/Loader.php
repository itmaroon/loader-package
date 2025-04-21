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

                if ($textdomain && file_exists($dir . '/../../languages')) {
                    load_plugin_textdomain(
                        $textdomain,
                        false,
                        plugin_basename(realpath($dir . '/../../')) . '/languages'
                    );
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
