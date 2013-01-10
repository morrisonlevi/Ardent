<?php

require 'setup.php';

define('HALF', SIZE / 2);

echo 'Doing ', HALF, " unsets on index ", HALF, " in array of size ",
    SIZE, " per iteration.\n",
    ITERATIONS, " iterations per test.\n\n";

$ardent = new Ardent\LinkedList();
$spl = new SplDoublyLinkedList();
$array = array();
for ($i = 0; $i < HALF; $i++) {
    $ardent[] = $i;
    $spl[] = $i;
    $array[] = $i;
}


$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    for ($j = 0; $j < HALF; $j++) $array[] = $j;
    $start = microtime(true);
    for ($j = 0; $j < HALF; $j++) {
        array_splice($array, HALF, 1);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "vanilla php array:\t$average\n";

$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    for ($j = 0; $j < HALF; $j++) $ardent[] = $j;
    $start = microtime(true);
    for ($j = 0; $j < HALF; $j++) {
        unset($ardent[HALF]);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "\Ardent\LinkedList:\t$average\n";


$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    for ($j = 0; $j < HALF; $j++) $spl[] = $j;
    $start = microtime(true);
    for ($j = 0; $j < HALF; $j++) {
        unset($spl[HALF]);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "\SplDoublyLinkedList:\t$average\n";



