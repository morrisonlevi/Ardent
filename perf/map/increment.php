<?php

require 'setup.php';

function populate($map) {
    for ($i = 0; $i < SIZE; $i++) {
        $map[$i] = $i * 2;
    }
    return $map;
}

$native = populate([]);
$hash = populate($hash);
$sorted = populate($sorted);

$runtimes = [];
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $native[$j] += 1;
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
printf("native:\t\t%f\n", array_sum($runtimes) / ITERATIONS);


$runtimes = [];
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $hash[$j] += 1;
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
printf("HashMap:\t%f\n", array_sum($runtimes) / ITERATIONS);


$runtimes = [];
for ($i = 0; $i < ITERATIONS; $i++) {
    $start = microtime(true);
    for ($j = 0; $j < SIZE; $j++) {
        $sorted[$j] += 1;
    }
    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
printf("SortedMap:\t%f\n", array_sum($runtimes) / ITERATIONS);

