<?php

namespace Ardent;

use IteratorIterator,
    Traversable;

class LazyCallbackIterator extends IteratorIterator {

    /**
     * @var array
     */
    protected $args;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var bool
     */
    protected $hasBeenLoaded;

    /**
     * @param Traversable $iterator
     * @param callable $callback Callback has the signature ($key, $value,...)
     * @param mixed... $varargs A variable amount of arguments you wish to pass
     */
    public function __construct(Traversable $iterator, $callback, $varargs = NULL) {
        parent::__construct($iterator);
        $this->callback = $callback;

        // args[0] and [1] will be used for $key and $value, respectively
        $this->args = func_get_args();
        $this->args[0] = NULL;
        $this->args[1] = NULL;
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
