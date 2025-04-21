<?php

// Itmar\Autoloader\Loader を読み込む（パッケージ内に定義されていると仮定）
if (!class_exists(\Itmar\Autoloader\Loader::class)) {
    require_once __DIR__ . '/Loader.php';
}

use Itmar\Autoloader\Loader;
// register() を呼び出して spl_autoload_register() を設定
$backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1); // 呼び出し元からパスを推測
$caller_file = $backtrace[0]['file'] ?? null;
if ($caller_file && file_exists(dirname($caller_file) . '/vendor/composer/autoload_psr4.php')) {
    Loader::register(dirname($caller_file) . '/vendor/composer/autoload_psr4.php');
}
