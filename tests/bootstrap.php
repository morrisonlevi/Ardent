<?php

spl_autoload_register(function ($class) {
    $path = 'src' . DIRECTORY_SEPARATOR . str_replace('\\',  DIRECTORY_SEPARATOR , $class) . '.php';
    if (file_exists($path)) {
        require $path;
    }
});
