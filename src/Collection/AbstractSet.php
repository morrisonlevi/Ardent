<?php

namespace Ardent\Collection;


abstract class AbstractSet implements Set {


    /**
     * Algorithms such as set difference need to be able to return a new
     * set object that includes any hashing or sorting functions needed but
     * without any data in the structure. This method provides that ability.
     *
     * @return Set
     */
    protected abstract function cloneEmpty();


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
    function difference(Set $that) {
        $difference = $this->cloneEmpty();

        if ($that === $this) {
            return $difference;
        }

        $this->addIf($difference, $this, negate([$that, 'has']));
        $this->addIf($difference, $that, negate([$this, 'has']));

        return $difference;
    }


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
    function intersection(Set $that) {
        $intersection = $this->cloneEmpty();

        $this->addIf($intersection, $this, [$that, 'has']);

        return $intersection;
    }


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
    function complement(Set $that) {
        $complement = $this->cloneEmpty();

        if ($that === $this) {
            return $complement;
        }

        $this->addIf($complement, $that, negate([$this, 'has']));

        return $complement;
    }


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
    function union(Set $that) {
        $union = $this->cloneEmpty();

        $this->for_each($this, [$union, 'add']);
        $this->for_each($that, [$union, 'add']);

        return $union;
    }


    private function for_each(\Traversable $i, callable $f) {
        foreach ($i as $item) {
            $f($item);
        }
    }


    private function addIf(Set $to, Set $from, callable $f) {
        foreach ($from as $value) {
            if ($f($value)) {
                $to->add($value);
            }
        }
    }

}
