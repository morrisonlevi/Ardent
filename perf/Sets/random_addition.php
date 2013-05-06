<?php

require __DIR__ . '/../../vendor/autoload.php';

$avl = new \Ardent\AvlTree();
$splay = new \Ardent\SplayTree();
$numeric = new \Ardent\SortedNumericSet();
$array = [];

$start = microtime(TRUE);
$stop = microtime(TRUE);
$max = 1000;

$a = [];
for ($i = 0; $i < $max; $i++) {
    $a[] = mt_rand();
}

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $avl->add($a[$i]);
}
$stop = microtime(TRUE);
printf("AvlSet:  \t%d random additions took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $splay->add($a[$i]);
}
$stop = microtime(TRUE);
printf("SplaySet:\t%d random additions took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $numeric->add($a[$i]);
}
$numeric->count(); // force sorting
$stop = microtime(TRUE);
printf("NumericSet:\t%d random additions took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $array[] = $a[$i];
}
array_unique($array, SORT_NUMERIC);
sort($array, SORT_NUMERIC);
$stop = microtime(TRUE);
printf("Array:   \t%d random additions took %fs.\n", $max, $stop - $start);

