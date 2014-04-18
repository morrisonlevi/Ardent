<?php

namespace Collections;

interface Enumerable extends Collection, \IteratorAggregate {


    function getIterator(): Enumerator;


} 