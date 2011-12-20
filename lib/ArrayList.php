<?php

namespace Spl;

require_once 'interface/Vector.php';

/**
 * An initial API for ArrayList. It would essentially be a high-level of
 * abstraction with a low-level implementation for performance. PHP's arrays 
 * are really poor if you just want true index-based datastructures.
 * 
 * Note that IteratorAggregate must come before Vector or PHP will fail to
 * work correctly.  This is a bug regarding Traversable.
 */
class ArrayList implements \IteratorAggregate, Vector, \Serializable {

    /**
     * @var \SplFixedArray
     */
    private $list;
    
    /**
     * @var int The number of slots used.
     */
    private $count;

    public function __construct() {
        $this->list = new \SplFixedArray(1);
        $this->count = 0;
    }

    public function offsetGet($offset) {
        if (filter_var($offset, FILTER_VALIDATE_INT) === false) {
            throw new \OutOfRangeException("Invalid index type: expected int");
        } else if ($offset < 0 || $offset >= $this->count) {
            throw new \OutOfBoundsException("Index out-of-bounds.");
        }

        return $this->list[$offset];
    }

    protected function grow() {
        $this->list->setSize($this->list->getSize() * 2);
    }

    public function offsetSet($offset, $value) {
        //Adding to the array
        if ($offset === null) {
            if ($this->count === $this->list->getSize()) {
                $this->grow();
            }

            $this->list[$this->count++] = $value;
        }
        //Setting an index
        else {
            if (filter_var($offset, FILTER_VALIDATE_INT) === false) {
                throw new \OutOfRangeException(
                        "Invalid index type: expected int"
                );
            } else if ($offset < 0 || $offset >= $this->list->getSize()) {
                throw new \OutOfBoundsException(
                        "Index '$offset' out-of-bounds."
                );
            }

            $this->list[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return  filter_var($offset, FILTER_VALIDATE_INT) !== false
                && $offset >= 0
                && $offset < $this->count;
        
    }

    public function offsetUnset($offset) {
        
        unset($this->list[$offset]);
    }

    public function count() {
        return $this->count;
    }

    public function isEmpty() {
        return $this->count === 0;
    }

    public function clear() {
        $this->list->setSize(1);
        unset($this->list[0]);
        $this->count = 0;
    }

    public function contains($item) {
        for ($i = 0; $i < $this->count; $i++) {
            $value = $this->list[$i];
            //the object checks are there to prevent PHP from issuing an
            //error.  They may not be needed.
            if ((is_object($value) && !is_object($item))
                || (!is_object($value) && is_object($item))) {
                continue;
            }
            if ($value == $item) {
                return true;
            }
        }

        return false;
    }

    public function serialize() {
        return serialize(
                array(
                    $this->count,
                    $this->list
                )
            );
    }

    public function unserialize($string) {
        $array = unserialize($string);
        if (!is_array($array)) {
            //error
        }
        if (!isset($array[0]) || filter_var($array[0], FILTER_VALIDATE_INT)===false) {
            // error
        }
        if (!isset($array[1]) || !($array[1] instanceof SplFixedArray)) {
            //error
        }
        if ($array[0] > count($array[1]) || $array[0] < 0) {
            //error
        }
        $this->count = $array[0];
        $this->list->fromArray($array[1]);
    }

    public function getIterator() {
        return new \ArrayIterator($this->list);
    }

}

?>
