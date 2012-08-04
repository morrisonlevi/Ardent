<?php

namespace Spl;

interface Observer {

    /**
     * @param Subject $subject
     *
     * @return void
     */
    function update(Subject $subject);

}
