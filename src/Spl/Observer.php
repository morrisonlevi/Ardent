<?php

namespace Spl;

interface Observer {

    /**
     * @abstract
     * @return void
     */
    function notify();
    
}
