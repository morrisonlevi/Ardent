<?php

namespace Ardent;

interface Observer {

    /**
     * @param Subject $subject
     *
     * @return void
     */
    function update(Subject $subject);

}
