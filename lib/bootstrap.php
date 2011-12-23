<?php

if (!defined('DIRECTORY_SEPERATOR')) {
    define('DIRECTORY_SEPERATOR', '/');
}

spl_autoload_register(function ($class) {
    $path = DIRECTORY_SEPERATOR . str_replace('\\', DIRECTORY_SEPERATOR, $class);
    $path = __DIR__ . $path . '.php';
    if (file_exists($path)) {
        require $path;
    }
});
