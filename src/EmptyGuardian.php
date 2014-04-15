<?php

namespace Collections;


trait EmptyGuardian {


    abstract function isEmpty();


    private function emptyGuard($method) {
        if ($this->isEmpty()) {
            throw new EmptyException(
                "{$method} cannot be called when the structure is empty"
            );
        }
    }


} 