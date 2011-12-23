<?php

namespace Spl\Interfaces;

use \Spl\Interfaces\Collection;

interface Set extends Collection {

    /**
     * Add a new element to the set, only if it does not already exist
     *
     * @param mixed $value The new element to add
     *
     * @return void
     */
    public function add($value);

    /**
     * Remove an element from the set, only if it already exists
     *
     * @param mixed $value The value to remove
     *
     * @return void
     */
    public function remove($value);

    /**
     * Return a new set which is the union of both sets (contains all elements)
     *
     * @param Set $set The set to union with the current set
     *
     * @return Set The union of the two sets
     */
    public function union(Set $set);

    /**
     * Return a new set which is the intersection of both sets (contains elements
     * which exist only in both sets)
     *
     * @param Set $set The set to intersect with the current set
     *
     * @return Set The intersection of the two sets
     */
    public function intersection(Set $set);

    /**
     * Return a new set which is the difference of this set with respect to the parameter
     *
     * @param Set $set The set to diff against the current one
     *
     * @return Set A set containing all elements from this set not found in the argument
     */
    public function difference(Set $set);

    /**
     * Is the passed in set a subset of the current set
     *
     * @param Set $set The set to check against
     *
     * @return boolean True if all elements in the parameter exist in the current set
     */
    public function isSubset(Set $set);
}
