<?php

namespace Collections;

class LinkedList implements \ArrayAccess, \Countable, Enumerator {

    use EmptyGuard;
    use IteratorCollection;

    /**
     * @var LinkedNode
     */
    private $head;

    /**
     * @var LinkedNode
     */
    private $tail;

    /**
     * @var LinkedNode
     */
    private $currentNode;

    private $currentOffset = 0;

    private $size = 0;


    /**
     * @return void
     */
    function __clone() {
        $that = $this->copyFromContext($this->head);
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
    function contains($value, callable $callback = NULL) {
        return $this->indexOf($value, $callback) >= 0;
    }


    /**
     * @param mixed $value
     * @param callable $callback [optional]
     * @return int
     */
    function indexOf($value, callable $callback = NULL) {
        if ($this->head === NULL) {
            return -1;
        }

        /**
         * @var callable $callback
         */
        $callback = $callback ?: '\Collections\equal';

        if ($callback($value, $this->currentNode->value)) {
            return $this->currentOffset;
        }

        $offset = 0;
        for ($node = $this->head; $node !== NULL; $node = $node->next, $offset++) {
            if ($callback($value, $node->value)) {
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
     * @throws TypeException
     */
    function offsetGet($offset) {
        $index = intGuard($offset);
        $this->existsGuard($index, __METHOD__);

        $this->seekUnsafe($index);

        return $this->currentNode->value;
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param int|NULL $offset
     * @param mixed $value
     * @return void
     * @throws IndexException
     * @throws TypeException
     */
    function offsetSet($offset, $value) {
        if ($offset === NULL) {
            $this->push($value);
            return;
        }

        $index = intGuard($offset);
        $this->existsGuard($index, __METHOD__);

        $this->seekUnsafe($index);
        $this->currentNode->value = $value;
    }


    /**
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param int $offset
     * @return void
     */
    function offsetUnset($offset) {
        if ($this->offsetExists($offset)) {
            $this->seekUnsafe($offset);
            $this->removeNode($this->currentNode, $offset);
        }
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
        $this->emptyGuard(__METHOD__);
        $value = $this->head->value;
        $this->removeNode($this->head, 0);
        return $value;
    }


    /**
     * @return mixed
     * @throws EmptyException if the LinkedList is empty.
     */
    function first() {
        $this->emptyGuard(__METHOD__);
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
    function pop() {
        $this->emptyGuard(__METHOD__);

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
    function last() {
        $this->emptyGuard(__METHOD__);

        return $this->tail->value;
    }


    /**
     * @param int $offset
     * @param mixed $value
     * @throws IndexException
     * @throws EmptyException
     * @returns void
     */
    function insertAfter($offset, $value) {
        $this->emptyGuard(__METHOD__);
        $index = intGuard($offset);
        $this->existsGuard($index, __METHOD__);

        if ($index == $this->size - 1) {
            $this->push($value);
            $this->currentNode = $this->tail->prev;
            $this->currentOffset = $this->size - 2;
            return;
        }

        $this->seekUnsafe($index);
        $this->insertBetween($this->currentNode, $this->currentNode->next, $value);
        $this->size++;
    }


    /**
     * @param int $offset
     * @param mixed $value
     * @throws IndexException
     * @throws TypeException
     * @throws EmptyException
     * @returns void
     */
    function insertBefore($offset, $value) {
        $this->emptyGuard(__METHOD__);
        $index = intGuard($offset);
        $this->existsGuard($index, __METHOD__);

        if ($index == 0) {
            $this->unshift($value);
            $this->currentNode = $this->head->next;
            $this->currentOffset = 1;
            return;
        }

        $this->seekUnsafe($index);

        $this->insertBetween($this->currentNode->prev, $this->currentNode, $value);
        $this->currentOffset++;
        $this->size++;

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
     * @return int
     */
    function key() {
        return $this->currentOffset;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed If the structure is empty this will return NULL
     */
    function current() {
        return $this->currentNode->value;
    }


    /**
     * @link http://php.net/manual/en/iterator.current.php
     * @return void
     */
    function rewind() {
        $this->currentNode = $this->head;
        $this->currentOffset = 0;
    }


    /**
     * @return void
     */
    function end() {
        $this->currentNode = $this->tail;
        $this->currentOffset = max(0, $this->size - 1);
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
        $index = intGuard($offset);
        $this->existsGuard($index, __METHOD__);
        $this->seekUnsafe($index);
    }


    /**
     * Extract the elements after the first of a list, which must be non-empty.
     * @return LinkedList
     * @throws StateException
     */
    function tail() {
        $this->emptyGuard(__METHOD__);
        return $this->copyFromContext($this->head->next);
    }


    private function seekUnsafe($offset) {

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


    private function copyFromContext(LinkedNode $context = null) {
        $list = new self();
        for ($n = $context; $n !== NULL; $n = $n->next) {
            $list->push($n->value);
        }
        return $list;
    }


    private function insertBetween(LinkedNode $a, LinkedNode $b, $value) {
        $link = new LinkedNode($value);

        $b->prev = $a->next = $link;
        $link->prev = $a;
        $link->next = $b;
    }


    private function existsGuard($offset, $method) {
        if (!$this->offsetExists($offset)) {
            throw new IndexException(
                "{$method} was called with invalid index: {$offset}"
            );
        }
    }


}
