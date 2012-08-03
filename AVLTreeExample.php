<?php

require 'bootstrap.php';

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