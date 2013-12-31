<?php

spl_autoload_register(function ($class) {
    switch ($class) {
        case 'Collections\\CollectionTestDriver':
            require __DIR__ . '/Collections/CollectionTestDriver.php';
    }
});

require __DIR__ . '/../vendor/autoload.php';