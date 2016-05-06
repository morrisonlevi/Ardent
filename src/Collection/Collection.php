<?php

namespace Ardent\Collection;

use Traversable;


interface Collection extends Traversable {

    /**
     * @return bool
     */
    function isEmpty();

}
