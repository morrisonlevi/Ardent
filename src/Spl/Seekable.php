<?php

namespace Spl;

interface Seekable {
    /**
     * @param int $position
     * @return int
     */
    function seek($position);
}
