<?php

namespace Ardent;

use IteratorIterator,
    Traversable;

class MappingIterator extends IteratorIterator {

    protected $args = [];

    /**
     * @var callable
     */
    protected $callback;

    protected $data;

    protected $hasBeenLoaded = FALSE;

    /**
     * @param Traversable $iterator
     * @param callable $callback Callback has the signature ($key, $value,...)
     * @param array $args An array of arguments that will be passed to the callback. They will not be passed as an array
     *                    but the values of the array.
     */
    public function __construct(Traversable $iterator, callable $callback, array $args = []) {
        parent::__construct($iterator);
        $this->callback = $callback;

        $this->args = array_merge([NULL, NULL], $args);
    }

    function current() {
        if (!$this->hasBeenLoaded) {
            $this->args[0] = $this->key();
            $this->args[1] = parent::current();
            $this->data = call_user_func_array(
                $this->callback,
                $this->args
            );

            $this->hasBeenLoaded = TRUE;
        }
        return $this->data;
    }

    function next() {
        parent::next();
        $this->hasBeenLoaded = FALSE;
    }

}
