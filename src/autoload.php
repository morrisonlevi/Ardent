<?php

namespace Collections;

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
        'Collections\\CountableIterator' =>  'Iterator/CountableIterator.php',
        'Collections\\CountableSeekableIterator'   =>  'Iterator/CountableSeekableIterator.php',
        'Collections\\EmptyException' =>  'Exception/EmptyException.php',
        'Collections\\Enumerable'   =>  'Enumerable.php',
        'Collections\\Enumerator'   =>  'Enumerator.php',
        'Collections\\Exception' =>   'Exception/Exception.php',
        'Collections\\FunctionException'  =>  'Exception/FunctionException.php',
        'Collections\\IndexException' =>  'Exception/IndexException.php',
        'Collections\\KeyException'   =>  'Exception/KeyException.php',
        'Collections\\LookupException'    =>  'Exception/LookupException.php',
        'Collections\\StateException' =>  'Exception/StateException.php',
        'Collections\\TypeException'  =>  'Exception/TypeException.php',
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
    ];

    if (isset($classMap[$className])) {
        require "$root/{$classMap[$className]}";
    }

}

