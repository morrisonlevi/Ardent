<?php

namespace Ardent;


use Ardent\Exception\KeyException;

/**
 * @package Ardent
 *
 * This class is highly experimental. DO NOT USE! This comment will be removed
 * when it is no longer considered harmful.
 */
class Trie implements \Countable {

    private $root = [];
    private $size = 0;
    private $separator = '';

    function __construct($separator = '') {
        $this->separator = $separator;
    }

    function offsetSet($key, $value) {
        if ($key === NULL) {
            throw new KeyException('NULL is not allowed as a Trie key');
        }

        $currentLevel =& $this->root;

        $data = $this->split($key);

        foreach ($data as $segment) {
            if (!isset($currentLevel['children'][$segment])) {
                $currentLevel['children'][$segment] = [];
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }

        if (!array_key_exists('data', $currentLevel)) {
            // Note that this cannot be done when adding a child to the trie.
            // Example: your trie is empty and and you add something that is
            // two levels deep.
            $this->size++;
        }
        $currentLevel['data'] = $value;
    }


    function search($key) {
        if ($key === NULL) {
            return FALSE;
        }

        $currentLevel =& $this->root;

        $data = $this->split($key);

        foreach ($data as $segment) {
            if (!isset($currentLevel['children'][$segment])) {
                return FALSE;
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }
        return array_key_exists('data', $currentLevel);
    }

    function count() {
        return $this->size;
    }

    private function split($key) {
        return $this->separator === ''
            ? str_split($key)
            : explode($this->separator, $key);
    }

}


