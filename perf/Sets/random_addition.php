<?php

require __DIR__ . '/../../vendor/autoload.php';

$avl = new \Ardent\AvlTree();
$splay = new \Ardent\SplayTree();
$array = [];

$start = microtime(TRUE);
$stop = microtime(TRUE);
$max = 10000;

$a = [];
for ($i = 0; $i < $max; $i++) {
    $a[] = mt_rand();
}


$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $avl->add($a[$i]);
}
$stop = microtime(TRUE);
printf("AvlTree:  \t%d random additions took %fs.\n", $max, $stop - $start);


$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $splay->add($a[$i]);
}
$stop = microtime(TRUE);
printf("SplayTree:\t%d random additions took %fs.\n", $max, $stop - $start);


$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $array[] = $a[$i];
}
array_unique($array, SORT_NUMERIC);
sort($array, SORT_NUMERIC);
$stop = microtime(TRUE);
printf("Array:   \t%d random additions took %fs.\n", $max, $stop - $start);

