<?php

namespace Ardent;

interface Subject {

    /**
     * @param Observer $observer
     *
     * @return void
     */
    function attach(Observer $observer);

    /**
     * @param Observer $observer
     *
     * @return void
     */
    function detach(Observer $observer);

}
