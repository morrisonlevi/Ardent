<?php

namespace Collections;


function negate(callable $f) {
    return function() use($f) {
        return !call_user_func_array($f, func_get_args());
    };
}


/**
 * @param $a
 * @param $b
 * @return int
 */
function compare($a, $b) {
    if ($a < $b) {
        return -1;
    } elseif ($b < $a) {
        return 1;
    } else {
        return 0;
    }
}


/**
 * @param $a
 * @param $b
 * @return bool
 */
function equal($a, $b) {
    return $a == $b;
}


/**
 * @param $a
 * @param $b
 * @return bool
 */
function same($a, $b) {
    return $a === $b;
}


/**
 * @param string $className
 * @return void
 */
function autoload($className) {
    static $root = __DIR__;
    static $classMap = [
        'Collections\\AbstractSet'	=>	'AbstractSet.php',
        'Collections\\ArrayIterator'   =>  'Iterator/ArrayIterator.php',
        'Collections\\AvlTree'	=>	'AvlTree.php',
        'Collections\\BinarySearchTree'	=>	'BinarySearchTree.php',
        'Collections\\BinaryTree'	=>	'BinaryTree.php',
        'Collections\\BinaryTreeIterator'  =>'Iterator/BinaryTreeIterator.php',
        'Collections\\Collection'	=>	'Collection.php',
        'Collections\\ConditionalMediator'	=>	'ConditionalMediator.php',
        'Collections\\CountableIterator'	=>	'Iterator/CountableIterator.php',
        'Collections\\CountableSeekableIterator'   =>  'Iterator/CountableSeekableIterator.php',
        'Collections\\EmptyException'	=>	'Exception/EmptyException.php',
        'Collections\\EmptyGuard'	=>	'EmptyGuard.php',
        'Collections\\Enumerable'	=>	'Enumerable.php',
        'Collections\\Enumerator'	=>	'Enumerator.php',
        'Collections\\Exception'	=>	'Exception/Exception.php',
        'Collections\\FunctionException'	=>	'Exception/FunctionException.php',
        'Collections\\IndexException'	=>	'Exception/IndexException.php',
        'Collections\\KeyException'	=>	'Exception/KeyException.php',
        'Collections\\LookupException'	=>	'Exception/LookupException.php',
        'Collections\\StateException'	=>	'Exception/StateException.php',
        'Collections\\TypeException'	=>	'Exception/TypeException.php',
        'Collections\\HashingMediator'	=>	'HashingMediator.php',
        'Collections\\HashMap'	=>	'HashMap.php',
        'Collections\\HashSet'	=>	'HashSet.php',
        'Collections\\FilteringIterator' =>  'Iterator/FilteringIterator.php',
        'Collections\\HashMapIterator' =>  'Iterator/HashMapIterator.php',
        'Collections\\HashSetIterator' =>  'Iterator/HashSetIterator.php',
        'Collections\\InOrderIterator' =>  'Iterator/InOrderIterator.php',
        'Collections\\IteratorCollection'  =>  'Iterator/IteratorCollection.php',
        'Collections\\IteratorCollectionAdapter' =>  'Iterator/IteratorCollectionAdapter.php',
        'Collections\\KeyIterator' =>  'Iterator/KeyIterator.php',
        'Collections\\LevelOrderIterator'  =>  'Iterator/LevelOrderIterator.php',
        'Collections\\LimitingIterator'    =>  'Iterator/LimitingIterator.php',
        'Collections\\LinkedListIterator'  =>  'Iterator/LinkedListIterator.php',
        'Collections\\LinkedQueueIterator' =>  'Iterator/LinkedQueueIterator.php',
        'Collections\\LinkedStackIterator' =>  'Iterator/LinkedStackIterator.php',
        'Collections\\MapIterator' =>  'Iterator/MapIterator.php',
        'Collections\\MappingIterator' =>  'Iterator/MappingIterator.php',
        'Collections\\PreOrderIterator'    =>  'Iterator/PreOrderIterator.php',
        'Collections\\PostOrderIterator'   =>  'Iterator/PostOrderIterator.php',
        'Collections\\QueueIterator'   =>  'Iterator/QueueIterator.php',
        'Collections\\SeekableIterator'    =>  'Iterator/SeekableIterator.php',
        'Collections\\SetIterator' =>  'Iterator/SetIterator.php',
        'Collections\\SkippingIterator'    =>  'Iterator/SkippingIterator.php',
        'Collections\\SlicingIterator' =>  'Iterator/SlicingIterator.php',
        'Collections\\SortedMapIterator' =>  'Iterator/SortedMapIterator.php',
        'Collections\\SortedSetIterator' =>  'Iterator/SortedSetIterator.php',
        'Collections\\StackIterator'   =>  'Iterator/StackIterator.php',
        'Collections\\LinkedList'	=>	'LinkedList.php',
        'Collections\\LinkedNode'	=>	'LinkedNode.php',
        'Collections\\LinkedQueue'	=>	'LinkedQueue.php',
        'Collections\\LinkedStack'	=>	'LinkedStack.php',
        'Collections\\Map'	=>	'Map.php',
        'Collections\\Mediator'	=>	'Mediator.php',
        'Collections\\Pair'	=>	'Pair.php',
        'Collections\\Queue'	=>	'Queue.php',
        'Collections\\Set'	=>	'Set.php',
        'Collections\\SortedMap'	=>	'SortedMap.php',
        'Collections\\SortedSet'	=>	'SortedSet.php',
        'Collections\\SplayNode' =>  'SplayNode.php',
        'Collections\\SplayTree' =>  'SplayTree.php',
        'Collections\\Stack'	=>	'Stack.php',
        'Collections\\ValueIterator'   =>   'Iterator/ValueIterator.php',
        'Collections\\Vector'	=>	'Vector.php',
        'Collections\\VectorIterator'  =>  'Iterator/VectorIterator.php',


        'Collections\\SNode'	=>	'SNode.php',
        'Collections\\SDataNode'	=>	'SDataNode.php',
        'Collections\\SLinkedList'	=>	'SLinkedList.php',
        'Collections\\STerminalNode'	=>	'STerminalNode.php',
    ];

    if (isset($classMap[$className])) {
        require "$root/{$classMap[$className]}";
    }

}


/**
 * @param $item
 *
 * @return string
 */
function hash($item) {
    if (is_object($item)) {
        return spl_object_hash($item);
    } elseif (is_scalar($item)) {
        return "s_$item";
    } elseif (is_resource($item)) {
        return "r_$item";
    } elseif (is_array($item)) {
        return 'a_' . md5(serialize($item));
    }

    return '0';
}


/**
 * @param mixed $i
 * @return int
 * @throws TypeException
 */
function intGuard($i) {
    if (filter_var($i, FILTER_VALIDATE_INT) === FALSE) {
        throw new TypeException;
    }
    return (int) $i;
}