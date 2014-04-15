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

        case 'Collections\\TestCase':
            require __DIR__ . '/Collections/TestCase.php';
            break;

        case 'Collections\\BinaryTreeIteratorTest':
            require __DIR__ . '/Collections/BinarySearchTree/BinaryTreeIteratorTest.php';
            break;
    }
});

require __DIR__ . '/../vendor/autoload.php';