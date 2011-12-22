<?php

spl_autoload_register(function ($class) {
    $path = '/' . str_replace('\\', '/', $class);
    $path = __DIR__ . $path . '.php';
    var_dump($path);
    if (file_exists($path)) {
        require $path;
    }
});