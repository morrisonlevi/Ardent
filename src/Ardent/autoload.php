<?php

namespace Ardent;

function autoload($className) {
    static $root = __DIR__;
    static $classMap = [
        'Ardent\\AbstractSet'	=>	'AbstractSet.php',
        'Ardent\\AvlTree'	=>	'AvlTree.php',
        'Ardent\\BinarySearchTree'	=>	'BinarySearchTree.php',
        'Ardent\\BinaryTree'	=>	'BinaryTree.php',
        'Ardent\\Collection'	=>	'Collection.php',
        'Ardent\\Countable' =>  'Countable.php',
        'Ardent\\ConditionalMediator'	=>	'ConditionalMediator.php',
        'Ardent\\Exception\\EmptyException' =>  'Exception/EmptyException.php',
        'Ardent\\Exception\\Exception' =>   'Exception/Exception.php',
        'Ardent\\Exception\\FunctionException'  =>  'Exception/FunctionException.php',
        'Ardent\\Exception\\IndexException' =>  'Exception/IndexException.php',
        'Ardent\\Exception\\KeyException'   =>  'Exception/KeyException.php',
        'Ardent\\Exception\\LookupException'    =>  'Exception/LookupException.php',
        'Ardent\\Exception\\StateException' =>  'Exception/StateException.php',
        'Ardent\\Exception\\TypeException'  =>  'Exception/TypeException.php',
        'Ardent\\HashingMediator'	=>	'HashingMediator.php',
        'Ardent\\HashMap'	=>	'HashMap.php',
        'Ardent\\HashSet'	=>	'HashSet.php',
        'Ardent\\Iterator\\ArrayIterator'   =>  'Iterator/ArrayIterator.php',
        'Ardent\\Iterator\\BinaryTreeIterator'  =>'Iterator/BinaryTreeIterator.php',
        'Ardent\\Iterator\\CountableIterator' =>  'Iterator/CountableIterator.php',
        'Ardent\\Iterator\\CountableSeekableIterator'   =>  'Iterator/CountableSeekableIterator.php',
        'Ardent\\Iterator\\FilteringIterator' =>  'Iterator/FilteringIterator.php',
        'Ardent\\Iterator\\HashMapIterator' =>  'Iterator/HashMapIterator.php',
        'Ardent\\Iterator\\HashSetIterator' =>  'Iterator/HashSetIterator.php',
        'Ardent\\Iterator\\InOrderIterator' =>  'Iterator/InOrderIterator.php',
        'Ardent\\Iterator\\IteratorCollection'  =>  'Iterator/IteratorCollection.php',
        'Ardent\\Iterator\\IteratorToCollectionAdapter' =>  'Iterator/IteratorToCollectionAdapter.php',
        'Ardent\\Iterator\\KeyIterator' =>  'Iterator/KeyIterator.php',
        'Ardent\\Iterator\\LevelOrderIterator'  =>  'Iterator/LevelOrderIterator.php',
        'Ardent\\Iterator\\LimitingIterator'    =>  'Iterator/LimitingIterator.php',
        'Ardent\\Iterator\\LinkedListIterator'  =>  'Iterator/LinkedListIterator.php',
        'Ardent\\Iterator\\LinkedQueueIterator' =>  'Iterator/LinkedQueueIterator.php',
        'Ardent\\Iterator\\LinkedStackIterator' =>  'Iterator/LinkedStackIterator.php',
        'Ardent\\Iterator\\MapIterator' =>  'Iterator/MapIterator.php',
        'Ardent\\Iterator\\MappingIterator' =>  'Iterator/MappingIterator.php',
        'Ardent\\Iterator\\PreOrderIterator'    =>  'Iterator/PreOrderIterator.php',
        'Ardent\\Iterator\\PostOrderIterator'   =>  'Iterator/PostOrderIterator.php',
        'Ardent\\Iterator\\QueueIterator'   =>  'Iterator/QueueIterator.php',
        'Ardent\\Iterator\\SeekableIterator'    =>  'Iterator/SeekableIterator.php',
        'Ardent\\Iterator\\SetIterator' =>  'Iterator/SetIterator.php',
        'Ardent\\Iterator\\SkippingIterator'    =>  'Iterator/SkippingIterator.php',
        'Ardent\\Iterator\\SlicingIterator' =>  'Iterator/SlicingIterator.php',
        'Ardent\\Iterator\\SortedMapIterator' =>  'Iterator/SortedMapIterator.php',
        'Ardent\\Iterator\\SortedSetIterator' =>  'Iterator/SortedSetIterator.php',
        'Ardent\\Iterator\\StackIterator'   =>  'Iterator/StackIterator.php',
        'Ardent\\Iterator\\VectorIterator'  =>  'Iterator/VectorIterator.php',
        'Ardent\\LinkedList'	=>	'LinkedList.php',
        'Ardent\\LinkedNode'	=>	'LinkedNode.php',
        'Ardent\\LinkedQueue'	=>	'LinkedQueue.php',
        'Ardent\\LinkedStack'	=>	'LinkedStack.php',
        'Ardent\\Map'	=>	'Map.php',
        'Ardent\\Mediator'	=>	'Mediator.php',
        'Ardent\\Pair'	=>	'Pair.php',
        'Ardent\\Queue'	=>	'Queue.php',
        'Ardent\\Set'	=>	'Set.php',
        'Ardent\\SortedMap'	=>	'SortedMap.php',
        'Ardent\\SortedSet'	=>	'SortedSet.php',
        'Ardent\\SplayNode' =>  'SplayNode.php',
        'Ardent\\SplayTree' =>  'SplayTree.php',
        'Ardent\\Stack'	=>	'Stack.php',
        'Ardent\\StructureCollection'	=>	'StructureCollection.php',
        'Ardent\\Vector'	=>	'Vector.php',
    ];

    if (isset($classMap[$className])) {
        require "$root/{$classMap[$className]}";
    }

}

