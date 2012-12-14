<?php

namespace Ardent;

class Pair {

    protected $first;

    protected $second;

    public function __construct($first, $second) {
        $this->first = $first;
        $this->second = $second;
    }

    public function first() {
        return $this->first;
    }

    public function second() {
        return $this->second;
    }

}
