<?php

require_once 'lib/bootstrap.php';

use Spl\HashSet;

$set = new HashSet();

$set->add(1);
$set->add('string');
$StdClass = new StdClass();
$set->add($StdClass);

print_r($set);

$set->remove(new StdClass());

print_r($set);

$set->remove(1);

print_r($set);


$odd = function($value) {
    return (int) $value;
};

print_r($set->map($odd));
print_r($set->filter($odd));

