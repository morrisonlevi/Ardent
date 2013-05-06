<?php

namespace Ardent;

class SortedNumericSet {

    private $array = [];
    private $dirty = FALSE;

    function add($item) {
        $this->array[] = $item;
        $this->dirty = TRUE;
    }

    function remove($item) {
        if ($this->dirty) {
            $this->array = array_unique($this->array, SORT_NUMERIC);
            sort($this->array);
            $this->dirty = FALSE;
        }
        $index = array_search($item, $this->array, $strict = TRUE);
        if ($index === FALSE) {
            return;
        }
        unset($this->array[$index]);
    }

    function containsItem($item) {
        if ($this->dirty) {
            $this->array = array_unique($this->array, SORT_NUMERIC);
            sort($this->array);
            $this->dirty = FALSE;
        }

        return array_search($item, $this->array, $strict = TRUE) !== FALSE;

    }

    function count() {
        if ($this->dirty) {
            $this->array = array_unique($this->array, SORT_NUMERIC);
            sort($this->array);
            $this->dirty = FALSE;
        }

        return count($this->array);
    }

}