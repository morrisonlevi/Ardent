<?php

namespace Ardent;


/**
 * @package Ardent
 *
 * This class is highly experimental. DO NOT USE! This comment will be removed
 * when it is no longer considered harmful.
 */
class Trie implements \Countable {

    private $root = [];
    private $size = 0;

    function add($key, $value) {
        $currentLevel =& $this->root;
        foreach (explode('/', $key) as $segment) {
            if (!isset($currentLevel['children'][$segment])) {
                $currentLevel['children'][$segment] = [];
                $this->size++;
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }
        $currentLevel['data'] = $value;
    }

    function count() {
        return $this->size;
    }


}


