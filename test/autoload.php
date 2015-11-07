<?php

require __DIR__ . '/../load.php';

spl_autoload_register(function ($class) {
    switch ($class) {

        case 'Ardent\\Collection\\SetIteratorTest':
            require __DIR__ . '/Collection/Set/SetIteratorTest.php';
            break;

        case 'Ardent\Collection\\SetTest':
            require __DIR__ . '/Collection/Set/SetTest.php';
            break;

        case 'Ardent\Collection\\TestCase':
            require __DIR__ . '/Collection/TestCase.php';
            break;

        case 'Ardent\Collection\\BinaryTreeIteratorTest':
            require __DIR__ . '/Collection/BinarySearchTree/BinaryTreeIteratorTest.php';
            break;
    }
});

