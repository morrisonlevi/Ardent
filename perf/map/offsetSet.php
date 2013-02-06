<?php

require 'setup.php';

$runtimes = [];
for ($i = 0; $i < ITERATIONS; $i++) {
    $map = [];
    $start = microtime(true);

    for ($j = 0; $j < SIZE; $j++) {
        $map[$j] = $j;
    }

    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
printf("native:\t\t%f\n", array_sum($runtimes) / ITERATIONS);


$runtimes = [];
for ($i = 0; $i < ITERATIONS; $i++) {
    $map = new Ardent\HashMap(function($item){return $item;});
    $start = microtime(true);

    for ($j = 0; $j < SIZE; $j++) {
        $map[$j] = $j;
    }

    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
printf("HashMap:\t%f\n", array_sum($runtimes) / ITERATIONS);


$runtimes = [];
for ($i = 0; $i < ITERATIONS; $i++) {
    $map = new Ardent\SortedMap;
    $start = microtime(true);

    for ($j = 0; $j < SIZE; $j++) {
        $map[$j] = $j;
    }

    $stop = microtime(true);
    $runtimes[] = $stop - $start;
}
printf("SortedMap:\t%f\n", array_sum($runtimes) / ITERATIONS);

