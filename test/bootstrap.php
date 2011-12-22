<?php

require_once '../lib/bootstrap.php';

spl_autoload_register(function ($class) {
    $path = '/' . str_replace('\\', '/', $class);
    $path = __DIR__ . $path . '.php';
    if (file_exists($path)) {
        require $path;
    }
});