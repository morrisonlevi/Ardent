<?php

spl_autoload_register(function($class) {
    $ds = DIRECTORY_SEPARATOR;
    $file = __DIR__ . "{$ds}src{$ds}" . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});