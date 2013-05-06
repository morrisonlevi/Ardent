<?php

require __DIR__ . '/../../vendor/autoload.php';

$avl = new \Ardent\AvlTree();
$splay = new \Ardent\SplayTree();
$numeric = new \Ardent\SortedNumericSet();
$array = [];

$start = microtime(TRUE);
$stop = microtime(TRUE);
$max = 10000;
$i = 0;

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $avl->add($i);
}
$stop = microtime(TRUE);
printf("AvlSet:  \t%d ordered additions took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $splay->add($i);
}
$stop = microtime(TRUE);
printf("SplaySet:\t%d ordered additions took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $numeric->add($i);
}
$numeric->count(); // force sorting
$stop = microtime(TRUE);
printf("NumericSet:\t%d ordered additions took %fs.\n", $max, $stop - $start);


$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $array[] = $i;
}
array_unique($array, SORT_NUMERIC);
sort($array, SORT_NUMERIC);
$stop = microtime(TRUE);
printf("Array:   \t%d ordered additions took %fs.\n", $max, $stop - $start);

