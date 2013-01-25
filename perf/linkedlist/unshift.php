<?php

require 'setup.php';

echo 'Doing ', SIZE, " insertions to head per iteration.\n",
    ITERATIONS, " iterations per test.\n\n";

$ardent = new Ardent\LinkedList();
$spl = new SplDoublyLinkedList();
$array = array();
/*
for ($i = 0; $i < SIZE; $i++) {
    $ardent[] = $i;
    $spl[] = $i;
    $array[] = $i;
}
*/

$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $array = array();
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        array_unshift($array, $j);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "vanilla php array:\t$average\n";

$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $ardent = new Ardent\LinkedList();
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $ardent->unshift($j);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "\Ardent\LinkedList:\t$average\n";


$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $spl = new SplDoublyLinkedList();
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $spl->unshift($j);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "\SplDoublyLinkedList:\t$average\n";



