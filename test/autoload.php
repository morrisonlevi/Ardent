<?php

spl_autoload_register(function ($class) {
    switch ($class) {
        case 'Collections\\CollectionTestDriver':
            require __DIR__ . '/Collections/CollectionTestDriver.php';
            break;

        case 'Collections\\SetIteratorTest':
            require __DIR__ . '/Collections/Set/SetIteratorTest.php';
            break;

        case 'Collections\\SetTest':
            require __DIR__ . '/Collections/Set/SetTest.php';
            break;
    }
});

require __DIR__ . '/../vendor/autoload.php';