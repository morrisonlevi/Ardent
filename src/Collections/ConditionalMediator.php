<?php

namespace Collections;

class ConditionalMediator implements Mediator {

    private $events = [];

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    function addListener($event, callable $callable) {
        $this->events[$event][] = $callable;
    }

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    function removeListener($event, callable $callable) {
        if (empty($this->events[$event])) {
            return;
        }

        $count = count($event);
        for ($i = 0; $i < $count; $i++) {
            if ($this->events[$event][$i] == $callable) {
                array_splice($this->events[$event], $i, 1);
                return;
            }
        }

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

        $args = array_slice(func_get_args(), 1);

        foreach ($this->events[$event] as $listener) {
            if (!call_user_func_array($listener, $args)) {
                break;
            }
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
            return $this->events[$event];
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
