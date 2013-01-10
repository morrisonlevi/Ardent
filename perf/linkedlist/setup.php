<?php

require '../../vendor/autoload.php';


define('ITERATIONS', 10);
define('SIZE', 10000);

function array_avg(array $array) {
    return array_sum($array) / sizeof($array);
}

