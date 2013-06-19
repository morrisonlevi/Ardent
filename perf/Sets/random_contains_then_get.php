<?php

require __DIR__ . '/../../vendor/autoload.php';

$avl = new \Ardent\AvlTree();
$splay = new \Ardent\SplayTree();
$array = [];

$start = microtime(TRUE);
$stop = microtime(TRUE);
$max = 1000;

$a = [];
for ($i = 0; $i < $max; $i++) {
    $a[] = $array[] = $rand = mt_rand();
    $avl->add($rand);
    $splay->add($rand);
}

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $element = $a[$i];
    if ($avl->containsItem($element)) {
        $avl->get($element);
    }
}
$stop = microtime(TRUE);
printf("AvlSet:  \t%d contains then gets took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $element = $a[$i];
    if ($splay->containsItem($element)) {
        $splay->get($element);
    }
}
$stop = microtime(TRUE);
printf("SplaySet:\t%d contains then gets took %fs.\n", $max, $stop - $start);

$start = microtime(TRUE);
for ($i = 0; $i < $max; $i++) {
    $element = $a[$i];
    if (array_search($element, $array, $strict = TRUE) !== FALSE) {
        $array[array_search($element, $array, $strict = TRUE)];
    }
}
array_unique($array, SORT_NUMERIC);
sort($array, SORT_NUMERIC);
$stop = microtime(TRUE);
printf("Array:   \t%d contains then gets took %fs.\n", $max, $stop - $start);

