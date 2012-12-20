<?php

namespace Ardent;

use Traversable;

interface Set extends Collection {

    /**
     * @param $item
     *
     * @return bool
     * @throws TypeException when $item is not the correct type.
     */
    function contains($item);

    /**
     * @param $item
     *
     * @return void
     * @throws TypeException when $item is not the correct type.
     */
    function add($item);

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

}
