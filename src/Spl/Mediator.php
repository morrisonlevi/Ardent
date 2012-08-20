<?php

namespace Spl;

interface Mediator {

    /**
     * @param string $event
     * @param callable $callable
     *
     * @throws TypeException if $callable is not callable.
     * @return void
     */
    function addListener($event, $callable);

    /**
     * @param string $event
     * @param callable $callable
     *
     * @return void
     */
    function removeListener($event, $callable);

    /**
     * @param string $event
     *
     * @return void
     */
    function removeListenersForEvent($event);

    /**
     * @return void
     */
    function removeAllListeners();

    /**
     * @param string$event
     *
     * @return void
     */
    function notify($event);

}
