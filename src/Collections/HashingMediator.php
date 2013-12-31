<?php

namespace Collections;

/**
 * HashingMediator enforces that a specific instance of a listener can only be
 * attached to the same event once, minus an edge case or two that are not
 * worth fussing over.
 */
class HashingMediator implements Mediator {

    protected $events = [];

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    function addListener($event, callable $callable) {
        $this->events[$event][$this->hash($callable)] = $callable;
    }

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    function removeListener($event, callable $callable) {
        unset($this->events[$event][$this->hash($callable)]);
    }

    function hash(callable $callable) {
        if (is_string($callable)) {
            return $callable;
        }
        if (is_array($callable)) {
            if (is_object($callable[0])) {
                return spl_object_hash($callable[0]) . $callable[1];
            }
            return "{$callable[0]}::{$callable[1]}";
        }
        return spl_object_hash($callable);
    }

    /**
     * @param string $event
     *
     * @return void
     */
    function removeEvent($event) {
        unset($this->events[$event]);
    }

    /**
     * @return void
     */
    function clear() {
        $this->events = [];
    }

    /**
     * @param string $event
     * @param ... $varargs
     * @return void
     */
    function notify($event, $varargs = NULL) {
        if (empty($this->events[$event])) {
            return;
        }
        
        $args = func_get_args();
        array_shift($args);

        foreach ($this->events[$event] as $listener) {
            call_user_func_array($listener, $args);
        }
    }

    /**
     * Note that this method should not generate warnings or errors when the
     * provided event does not exist.
     *
     * @param string $event
     * @return array
     */
    function getListeners($event) {
        $eventExists = array_key_exists($event, $this->events);
        if ($eventExists && is_array($this->events[$event])) {
            return array_values($this->events[$event]);
        }
        return [];
    }

    /**
     * @return array of event names
     */
    function getEvents() {
        return array_keys($this->events);
    }

}
