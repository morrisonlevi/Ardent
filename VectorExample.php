<?php

require 'bootstrap.php';

use Spl\StdVector;

$vector = new StdVector;

$vector[] = 1; //append to the vector
$vector[0] = 0; //set an item

// $vector[new StdObject()] = 1; //throws Spl\TypeException
// $vector[1] = 1; //throws Spl\OutOfBoundsException
unset($vector[1]); //but this is okay

$vector[] = 2;
$vector[] = 4;
$vector[] = 6;
$vector[] = 8;
$vector[] = 10;

$slice = $vector->slice(2, 2); //slice contains [4 , 6]

