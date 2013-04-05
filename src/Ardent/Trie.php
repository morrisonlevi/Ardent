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

    /**
     * @var callable
     */
    private $split;

    /**
     * @param callable $split function(string $key) returns an array of segments
     */
    function __construct(callable $split) {
        $this->split = $split;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @throws Exception\KeyException
     */
    function offsetSet($key, $value) {
        if ($key === NULL) {
            throw new KeyException('NULL is not allowed as a Trie key');
        }

        $currentLevel =& $this->root;

        $data = call_user_func($this->split, $key);

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

    /**
     * @param string $key
     * @return bool
     */
    function offsetExists($key) {
        if ($key === NULL) {
            return FALSE;
        }

        $currentLevel =& $this->root;

        $data = call_user_func($this->split, $key);

        foreach ($data as $segment) {
            if (!isset($currentLevel['children'][$segment])) {
                return FALSE;
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }
        return array_key_exists('data', $currentLevel);
    }

    /**
     * @param string $key
     * @return mixed
     */
    function offsetGet($key) {
        $currentLevel =& $this->root;

        $data = call_user_func($this->split, $key);

        foreach ($data as $segment) {
            $currentLevel =& $currentLevel['children'][$segment];
        }

        return $currentLevel['data'];
    }

    /**
     * @param string $key
     */
    function offsetUnset($key) {
        $currentLevel =& $this->root;
        $data = call_user_func($this->split, $key);
        foreach ($data as $segment) {
            if (!isset($currentLevel['children'][$segment])) {
                break;
            }
            $currentLevel =& $currentLevel['children'][$segment];
        }
        unset($currentLevel['data']);
    }

    /**
     * @param string $key
     * @param callable $keyToSegment function(string $key) returns string to use for matching purposes.
     * @param callable $onSuccess function($value) returns void
     * @param callable $onFailure function() returns void
     */
    function offsetMatch($key, callable $keyToSegment, callable $onSuccess, callable $onFailure) {
        $currentLevel =& $this->root;
        $data = call_user_func($this->split, $key);
        foreach ($data as $segmentKey) {
            /** @var mixed $segment */
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

}

