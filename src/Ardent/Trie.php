<?php

namespace Ardent;


use Ardent\Exception\KeyException;

/**
 * @package Ardent
 *
 * This class is highly experimental. DO NOT USE! This comment will be removed
 * when it is no longer considered harmful.
 */
class Trie implements \Countable, \ArrayAccess {

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

    function offsetExists($key) {
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

    function offsetGet($key) {
        $currentLevel =& $this->root;

        $data = $this->split($key);

        foreach ($data as $segment) {
            $currentLevel =& $currentLevel['children'][$segment];
        }

        return $currentLevel['data'];
    }

    function offsetUnset($key) {
        $currentLevel =& $this->root;
        $data = $this->split($key);
        foreach ($data as $segment) {
            if (!isset($currentLevel['children'][$segment])) {
                break;
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }
        unset($currentLevel['data']);
    }

    function offsetMatch($key, callable $keyToSegment, callable $onSuccess, callable $onFailure) {
        $currentLevel =& $this->root;
        $data = $this->split($key);
        foreach ($data as $segmentKey) {
            $segment = $keyToSegment($segmentKey);
            if (!isset($currentLevel['children'][$segment])) {
                $onFailure();
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }
        if (!array_key_exists('data', $currentLevel)) {
            $onFailure();
        }
        $onSuccess($currentLevel['data']);
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

