<?php

require 'setup.php';

echo 'Doing ', SIZE, " gets/sets per iteration.\n",
    ITERATIONS, " iterations per test.\n\n";

$ardent = new Ardent\LinkedList();
$spl = new SplDoublyLinkedList();
$array = array();
for ($i = 0; $i < SIZE; $i++) {
    $ardent[] = $i;
    $spl[] = $i;
    $array[] = $i;
}


$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $current = $array[$j];
        $array[$j] = $current + 1;
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "vanilla php array:\t$average\n";

$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $current = $ardent[$j];
        $ardent[$j] = $current + 1;
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "\Ardent\LinkedList:\t$average\n";


$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $current = $spl[$j];
        $spl[$j] = $current + 1;
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
$average = array_avg($runtimes);
echo "\SplDoublyLinkedList:\t$average\n";



