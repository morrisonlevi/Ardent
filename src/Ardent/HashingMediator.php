<?php

namespace Ardent;

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
    function removeListenersForEvent($event) {
        $this->events[$event] = [];
    }

    /**
     * @return void
     */
    function removeAllListeners() {
        $this->events = [];
    }

    /**
     * @param $event
     *
     * @return void
     */
    function notify($event) {
        if (empty($this->events[$event])) {
            return;
        }
        
        $args = func_get_args();
        array_shift($args);

        foreach ($this->events[$event] as $listener) {
            call_user_func_array($listener, $args);
        }
    }

}
