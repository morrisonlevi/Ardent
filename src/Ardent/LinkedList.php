<?php

namespace Ardent;

use ArrayAccess;

class LinkedList implements ArrayAccess, Collection {

    /**
     * @var LinkedNode
     */
    private $head;

    /**
     * @var LinkedNode
     */
    private $tail;

    /**
     * @var int
     */
    private $size = 0;

    /**
     * @var LinkedNode
     */
    private $currentNode;

    /**
     * @var int
     */
    private $currentOffset;

    /**
     * @return void
     */
    function __clone() {
        $that = $this->copyRange(0, $this->size);
        $this->head = $that->head;
        $this->tail = $that->tail;
        $this->currentNode = $that->currentNode;
        $this->currentOffset = $that->currentOffset;
    }

    /**
     * @param mixed $value
     * @param callable $callback [optional]
     *
     * @return bool
     * @throws TypeException when $object is not the correct type.
     */
    function contains($value, $callback = NULL) {
        if ($this->head === NULL) {
            return FALSE;
        }

        return $this->indexOf($value, $callback) !== -1;
    }

    /**
     * @param $value
     * @param callable $callback [optional]
     * @return int
     */
    function indexOf($value, $callback = NULL) {
        if ($this->head === NULL) {
            return -1;
        }

        $areEqual = $callback ?: array($this, '__equals');

        if (call_user_func($areEqual, $this->currentNode->value, $value)) {
            return $this->currentOffset;
        }

        $offset = 0;
        for ($node = $this->head; $node !== NULL; $node = $node->next, $offset++) {
            if (call_user_func($areEqual, $value, $node->value)) {
                $this->currentOffset = $offset;
                $this->currentNode = $node;
                return $offset;
            }
        }

        return -1;
    }

    /**
     * @return bool
     */
    function isEmpty() {
        return $this->head === NULL;
    }

    /**
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return LinkedListIterator
     */
    function getIterator() {
        return new LinkedListIterator(clone $this);
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param int $offset
     * @return boolean
     */
    function offsetExists($offset) {
        return $offset < $this->size && $offset >= 0;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param int $offset
     * @return mixed
     * @throws IndexException
     */
    function offsetGet($offset) {
        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        $this->__seek($offset);

        return $this->currentNode->value;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param int|NULL $offset
     * @param mixed $value
     * @return void
     * @throws IndexException
     */
    function offsetSet($offset, $value) {
        if ($offset === NULL) {
            $this->push($value);
            return;
        }

        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        $this->__seek($offset);
        $this->currentNode->value = $value;
    }

    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param int $offset
     * @return void
     */
    function offsetUnset($offset) {
        if (!$this->offsetExists($offset)) {
            return;
        }

        $this->__seek($offset);
        $this->removeNode($this->currentNode, $offset);
    }

    /**
     * @link http://php.net/manual/en/countable.count.php
     * @return int
     */
    function count() {
        return $this->size;
    }

    /**
     * @param mixed $object
     *
     * @throws TypeException if $object is not the correct type.
     * @throws FullException if the LinkedList is full.
     * @return void
     */
    function push($object) {
        $node = new LinkedNode($object);
        $this->size++;

        if ($this->tail === NULL) {
            $this->tail
                = $this->head
                = $this->currentNode
                = $node;
            return;
        }

        $tail = $this->tail;
        $tail->next = $node;
        $node->prev = $tail;
        $this->currentNode = $this->tail = $node;
        $this->currentOffset = $this->size - 1;
    }


    /**
     * @return mixed
     * @throws EmptyException if the LinkedList is empty.
     */
    function shift() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        $value = $this->head->value;
        $this->removeNode($this->head, 0);
        return $value;
    }

    /**
     * @return mixed
     * @throws EmptyException if the LinkedList is empty.
     */
    function peekFront() {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->head->value;
    }

    /**
     * @param mixed $value
     * @return void
     * @throws FullException if the LinkedList is full.
     */
    function unshift($value) {
        $node = new LinkedNode($value);
        $this->size++;

        if ($this->head === NULL) {
            $this->head
                = $this->tail
                = $this->currentNode
                = $node;
            return;
        }

        $this->head->prev = $node;
        $node->next = $this->head;
        $this->currentNode = $this->head = $node;

        $this->currentOffset = 0;
    }

    /**
     * @throws EmptyException if the LinkedList is empty.
     * @return mixed
     */
    function pop(){
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        $value = $this->tail->value;
        $this->removeNode($this->tail, $this->size - 1);

        $this->currentNode = $this->tail;
        $this->currentOffset = $this->size - 1;

        return $value;
    }

    /**
     * @throws EmptyException if the LinkedList is empty.
     * @return mixed
     */
    function peekBack(){
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        return $this->tail->value;
    }

    function insertAfter($offset, $value) {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        if ($offset == $this->size - 1) {
            $this->push($value);
            $this->currentNode = $this->tail->prev;
            $this->currentOffset = $this->size - 2;
            return;
        }

        $this->__seek($offset);

        $newNode = new LinkedNode($value);
        $this->currentNode->next
            = $this->currentNode->next->prev
            = $newNode;

        $this->size++;
    }

    function insertBefore($offset, $value) {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }

        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        if ($offset == 0) {
            $this->unshift($value);
            $this->currentNode = $this->head->next;
            $this->currentOffset = 1;
            return;
        }

        $this->__seek($offset);

        $newNode = new LinkedNode($value);

        $this->currentNode->prev
            = $this->currentNode->prev->next
            = $newNode;

        $this->currentOffset++;
        $this->size++;

    }

    /**
     * @param int $start
     * @param int $count
     * @return LinkedList
     * @throws EmptyException
     * @throws IndexException
     * @throws TypeException
     */
    function slice($start, $count = NULL) {
        if ($this->isEmpty()) {
            throw new EmptyException;
        }
        if (filter_var($start, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException(
                'Argument 1 of LinkedList::slice must be an integer'
            );
        }
        if ($count !== NULL && filter_var($count, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException(
                'If used, argument 2 of LinkedList::slice must be an integer'
            );
        }
        if ($start >= $this->size) {
            throw new IndexException(
                'Argument 1 of LinkedList::slice cannot be greater than or equal to the list size'
            );
        }
        if ($start < (-1 * $this->size)) {
            throw new IndexException(
                'Argument 1 of LinkedList::slice cannot be smaller than the lists negative size'
            );
        }

        if ($start < 0) {
            // add because start is a negative number
            $start = $this->size + $start;
        }

        $stop = $start + $count;

        if ($count < 0) {
            //add because $count is negative
            $stop = $this->size + $count;
        } elseif ($count === NULL || $stop > $this->size) {
            $stop = $this->size;
        }


        return $this->copyRange($start, $stop);
    }

    /**
     * @link http://php.net/manual/en/iterator.next.php
     * @return void
     */
    function next() {
        if ($this->currentNode !== NULL) {
            $this->currentNode = $this->currentNode->next;
            $this->currentOffset++;
        }
    }

    /**
     * @return void
     */
    function prev() {
        if ($this->currentNode !== NULL) {
            $this->currentNode = $this->currentNode->prev;
            $this->currentOffset--;
        }
    }

    /**
     * @link http://php.net/manual/en/iterator.key.php
     * @return int If the structure is empty, this value is null
     */
    function key() {
        if ($this->currentNode !== NULL) {
            return $this->currentOffset;
        }
        return NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed If the structure is empty this will return NULL
     */
    function current() {
        if ($this->currentNode !== NULL) {
            return $this->currentNode->value;
        }
        return NULL;
    }

    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return void
     */
    function rewind() {
        $this->currentNode = $this->head;

        if ($this->currentNode !== NULL) {
            $this->currentOffset = 0;
        }
    }

    /**
     * @return void
     */
    function end() {
        $this->currentNode = $this->tail;

        if ($this->currentNode !== NULL) {
            $this->currentOffset = $this->size - 1;
        }
    }

    /**
     * @link http://php.net/manual/en/iterator.valid.php
     * @return bool
     */
    function valid() {
        return $this->currentNode !== NULL;
    }

    /**
     * @param int $offset
     * @return void
     * @throws IndexException
     * @throws TypeException
     */
    function seek($offset) {
        if (filter_var($offset, FILTER_VALIDATE_INT) === FALSE) {
            throw new TypeException;
        }

        if (!$this->offsetExists($offset)) {
            throw new IndexException;
        }

        $this->__seek($offset);
    }

    private function __seek($offset) {

        if ($offset == $this->currentOffset) {
            return;
        }

        if ($offset == 0) {
            $this->currentOffset = 0;
            $this->currentNode = $this->head;
            return;
        }

        if ($offset == $this->size - 1) {
            $this->currentOffset = $this->size - 1;
            $this->currentNode = $this->tail;
            return;
        }

        $node = $this->currentNode;

        $currentDiff = $this->currentOffset - $offset;
        if ($currentDiff < 0) {
            for ($i = 0; $i > $currentDiff; $i--) {
                $node = $node->next;
            }
        } else {
            for ($i = 0; $i < $currentDiff; $i++) {
                $node = $node->prev;
            }
        }

        $this->currentOffset = $offset;
        $this->currentNode = $node;
    }


    private function removeNode(LinkedNode $node, $offset) {
        $current = NULL;

        if ($node->next !== NULL) {
            $this->currentNode = $node->next;
            $this->currentNode->prev = $node->prev;
        } else {
            $this->currentNode = $this->tail = $node->prev;
            $this->currentOffset = $offset - 1;
        }

        if ($node->prev !== NULL) {
            $node->prev->next = $node->next;
        } else {
            $this->head = $node->next;
        }

        $this->size--;
    }

    private function copyRange($start, $finish) {
        $that = new LinkedList();

        $this->__seek($start);

        for ($node = $this->currentNode, $i = $start; $i < $finish; $i++, $node = $node->next) {
            $that->push($node->value);
        }

        return $that;
    }

    /**
     * PhpStorm thinks this method is unused, but it is used in indexOf.
     *
     * @param $a
     * @param $b
     * @return bool
     */
    private function __equals($a, $b) {
        return $a == $b;
    }

}
