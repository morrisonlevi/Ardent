<?php

require 'bootstrap.php';

error_reporting(E_ALL & E_STRICT);

$tree = new Spl\AVLTree();

$tree->add(5);
$tree->add(10);
$tree->add(2);
$tree->add(7);
$tree->add(12);
$tree->add(1);
$tree->add(3);

print "In-Order:\n";
$inOrderIterator = $tree->getIterator($tree::TRAVERSE_IN_ORDER);
foreach ($inOrderIterator as $item) {
    print "$item\n";
}

print "\nPre-Order:\n";
$preOrderIterator = $tree->getIterator($tree::TRAVERSE_PRE_ORDER);
foreach ($preOrderIterator as $item) {
    print "$item\n";
}

print "\nLevel-Order:\n";
$levelOrderIterator = $tree->getIterator($tree::TRAVERSE_LEVEL_ORDER);
foreach ($levelOrderIterator as $item) {
    print "$item\n";
}

print "\nPost-Order:\n";
$postOrderIterator = $tree->getIterator($tree::TRAVERSE_POST_ORDER);
foreach ($postOrderIterator as $item) {
    print "$item\n";
}


print "\nGet(3):";
print $tree->get(3);
print "\n";

print "\nMap:\n";
$map = new Spl\SortedMap();
$map[1] = 2;
$map[2] = 4;
$map[3] = 6;

foreach ($map as $key) {
    print "$key => " . $map->get($key) . "\n";
}