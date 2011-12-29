<?php

namespace Spl;

use \IteratorAggregate;
use \Spl\Interfaces\Set;

/**
 * An initial HashSet implementation.  Currently it is counting on the hashing
 * algorithm to produce unique values for everything. This will work perfectly
 * for objects.  It treats numbers as if they are strings.  Any collisions in
 * this hash are PHP's fault. 
 */
class HashSet implements IteratorAggregate, Set {

    private $set;

    public function __construct() {
        $this->set = array();
    }

    /**
     * Returns true if the $value can be hashed.
     *
     * @param mixed $value The value to check if it can be hashed.
     * @return bool Returns true if the $value can be hashed. False otherwise.
     */
    protected function canHash($value) {
        return is_object($value) || is_string($value) || is_numeric($value);
    }

    /**
     * Returns the hash for the given value. The only values that can currently
     * be hashed are objects, strings, and numbers.
     *
     * @param mixed $value The value to be hashed.
     * @return string The hash for the given value.
     * @throws LogicException if the value cannot be hashed.
     */
    protected function hash($value) {
        // Prefix the hash with the type of thing being hashed to prevent 
        // collisions.  It may not be the best solution, but it works.
        // s_ is for scalar, o_ is for object
        if (is_object($value)) {
            return 'o_' . spl_object_hash($value);
        } else if (is_string($value) || is_numeric($value)) {
            return 's_' . $value;
        } else {
            throw new LogicException();
        }
    }

    /**
     * Add a new element to the set, only if it does not already exist
     *
     * @param mixed $value The new element to add
     *
     * @return void
     * @throws LogicException when $value cannot be hashed.
     */
    public function add($value) {
        $this->set[$this->hash($value)] = $value;
    }

    /**
     * Remove an element from the set, only if it already exists
     *
     * @param mixed $value The value to remove
     * @return void
     * @throws LogicException when $value cannot be hashed.
     */
    public function remove($value) {
        unset($this->set[$this->hash($value)]);
    }

    /**
     * Return a new set which is the union of both sets (contains all elements)
     *
     * @param Set $set The set to union with the current set
     *
     * @return Set The union of the two sets
     */
    public function union(Set $set) {
        $newSet = new self();

        $newSet->set = $set->toArray() + $this->set;

        return $newSet;
    }

    /**
     * Return a new set which is the intersection of both sets (contains elements
     * which exist only in both sets)
     *
     * @param Set $set The set to intersect with the current set
     *
     * @return Set The intersection of the two sets
     */
    public function intersection(Set $set) {
        $newSet = new self();

        $newSet->set = array_intersect($set->toArray(), $this->set);

        return $newSet;
    }

    /**
     * Return a new set which is the difference of this set with respect to the parameter
     *
     * @param Set $set The set to diff against the current one
     *
     * @return Set A set containing all elements from this set not found in the argument
     */
    public function difference(Set $set) {
        $newSet = new self();

        $newSet->set = array_diff($set->toArray(), $this->set);

        return $newSet;
    }

    /**
     * Is the passed in set a subset of the current set
     *
     * @param Set $set The set to check against
     *
     * @return boolean True if all elements in the parameter exist in the current set
     */
    public function isSubset(Set $set) {
        return rand(0,1); //broken.
    }

    public function toArray() {
        return $this->set;
    }

    public function isEmpty() {
        return count($this->set);
    }

    public function count() {
        return count($this->set);
    }

    public function getIterator() {
        return new HashSetIterator($this);
    }

    public function contains($value) { 
        return $this->canHash($value)
        && array_key_exists($this->hash($value), $this->set);
    }

    public function map($callback) {
        if (!is_callable($callback)) {
            throw new BadFunctionCallException();
        }

        $set = new self();
        foreach ($this->set as $value) {
            $set->add($callback($value));
        }
        return $set;
    }

    public function filter($callback) {
        $set = new self();
        $setArray = &$set->set;
        foreach ($this->set as $key => $value) {
            if ($callback($value)) {
                $setArray[$key] = $value;
            }
        }
        return $set;
    }

    public function clear() {
        $this->set = array();
    }
}
