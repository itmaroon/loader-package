<?php

// Itmar\Autoloader\Loader を読み込む（パッケージ内に定義されていると仮定）
require_once __DIR__ . '/Loader.php';

// register() を呼び出して spl_autoload_register() を設定
Itmar\Autoloader\Loader::register(dirname(__DIR__, 3) . '/composer/autoload_psr4.php');
