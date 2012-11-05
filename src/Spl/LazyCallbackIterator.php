<?php

namespace Spl;

use IteratorIterator,
    Traversable;

class LazyCallbackIterator extends IteratorIterator {

    protected $hasBeenLoaded;
    protected $data;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var array
     */
    protected $args;

    /**
     * @param Traversable $iterator
     * @param callable $callback Callback has the signature ($key, $value,...)
     */
    public function __construct(Traversable $iterator, $callback) {
        parent::__construct($iterator);
        $this->callback = $callback;

        // args[0] and [1] will be used for $key and $value, respectively
        $this->args = func_get_args();
        $this->args[0] = NULL;
        $this->args[1] = NULL;
    }

    function next() {
        parent::next();
        $this->hasBeenLoaded = FALSE;
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

}
