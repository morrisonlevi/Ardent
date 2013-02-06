<?php

require_once '../../vendor/autoload.php';

define('ITERATIONS', 25);
define('SIZE', 1000);

$native = [];
$hash = new Ardent\HashMap(function($int) {
    return $int;
});
$sorted = new Ardent\SortedMap();


