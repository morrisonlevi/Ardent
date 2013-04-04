<?php

namespace Ardent;

use Ardent\Exception\TypeException;

interface Set extends Collection, \IteratorAggregate {

    /**
     * Note that if the item is considered equal to an already existing item
     * in the set that it will be replaced.
     *
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function add($item);

    /**
     * @param mixed $item
     * @return bool
     */
    function containsItem($item);

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function remove($item);

    /**
     * Creates a set that contains the items in the current set that are not
     * contained in the provided set.
     *
     * Formally:
     * A - B = {x : x ∈ A ∧ x ∉ B}
     *
     * @param Set $that
     * @return Set
     */
    function difference(Set $that);

    /**
     * Creates the set that contains the items in the current set that are not
     * contained in the provided set, as well as items that are in the
     * provided set that are not in the current set.
     *
     * Formally:
     * A ⊖ B = {x : x ∈ (A \ B) ∨ (B \ A)}
     *
     * @param Set $that
     * @return Set
     */
    function symmetricDifference(Set $that);

    /**
     * Creates a new set that contains the items that are in current set that
     * are also contained in the provided set.
     *
     * Formally:
     * A ∩ B = {x : x ∈ A ∧ x ∈ B}
     *
     * @param Set $that
     * @return Set
     */
    function intersection(Set $that);

    /**
     * Creates a new set which contains the items that exist in the provided
     * set and do not exist in the current set.
     *
     * Formally:
     * B \ A = {x: x ∈ A ∧ x ∉ B}
     *
     * @param Set $that
     * @return Set
     */
    function relativeComplement(Set $that);

    /**
     * Creates a new set that contains the items of the current set and the
     * items of the provided set.
     *
     * Formally:
     * A ∪ B = {x: x ∈ A ∨ x ∈ B}
     *
     * @param Set $that
     * @return Set
     */
    function union(Set $that);

    /**
     * @param Set $that
     * @return bool
     */
    function isSubsetOf(Set $that);

    /**
     * @param Set $that
     * @return bool
     */
    function isStrictSubsetOf(Set $that);

    /**
     * @param Set $that
     * @return bool
     */
    function isSupersetOf(Set $that);

    /**
     * @param Set $that
     * @return mixed
     */
    function isStrictSupersetOf(Set $that);

}
