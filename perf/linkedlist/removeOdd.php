<?php

require 'setup.php';

define("HALF", SIZE / 2);

echo 'Removing half of ', SIZE ," per iteration.\n",
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
    for ($j = SIZE - 1; $j > 0; $j -= 2) {
        array_splice($array, $j, 1);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
    for ($j = 0; $j < HALF; $j++) {
        $array[] = $j;
    }
}
$average = array_avg($runtimes);
echo "vanilla php array:\t$average\n";

$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = SIZE - 1; $j > 0; $j -= 2) {
        unset($ardent[$j]);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
    for ($j = 0; $j < HALF; $j++) {
        $ardent[] = $j;
    }
}
$average = array_avg($runtimes);
echo "\Ardent\LinkedList:\t$average\n";


$runtimes = array();
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = SIZE - 1; $j > 0; $j -= 2) {
        unset($spl[$j]);
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
    for ($j = 0; $j < HALF; $j++) {
        $spl[] = $j;
    }
}
$average = array_avg($runtimes);
echo "\SplDoublyLinkedList:\t$average\n";



