<?php

namespace Spl;

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
     * Creates a set that contains the items in the current set that are not
     * contained in the provided set.
     *
     * @param Set $that
     * @return Set
     */
    function difference(Set $that) {
        $difference = $this->cloneEmpty();

        if ($that === $this) {
            return $difference;
        }

        foreach ($this as $item) {
            if (!$that->contains($item)) {
                $difference->add($item);
            }
        }

        return $difference;
    }

}
