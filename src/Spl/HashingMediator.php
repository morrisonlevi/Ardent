<?php

namespace Spl;

/**
 * HashingMediator enforces that a specific instance of a listener can only be
 * attached to the same event once. It does this by using the hash returned
 * by spl_object_hash.
 */
class HashingMediator implements Mediator {

    protected $events = array();

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    public function addListener($event, $callable) {
        $this->events[$event][$this->hash($callable)] = $callable;
    }

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    public function removeListener($event, $callable) {
        unset($this->events[$event][$this->hash($callable)]);
    }

    protected function hash($callable) {
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
    public function removeListenersForEvent($event) {
        $this->events[$event] = array();
    }

    /**
     * @return void
     */
    public function removeAllListeners() {
        $this->events = array();
    }

    /**
     * @param $event
     *
     * @return void
     */
    public function notify($event) {
        $args = func_get_args();
        array_shift($args);

        foreach ($this->events[$event] as $listener) {
            call_user_func_array($listener, $args);
        }
    }

}
