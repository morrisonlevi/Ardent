<?php

namespace Ardent;

use Countable,
    IteratorAggregate;

/**
 * A collection is countable and has an external iterator. The one helper
 * method is `isEmpty` which is a frequently used method.
 *
 * Note that Collection does not extend Serializable. This is because all
 * objects can be serialized and unserialized by PHP, and the interface is used
 * only for custom serializing. If the extension ever moves to PECL then the
 * structures will implement Serializable.
 */
interface Collection extends Countable, IteratorAggregate /*, \Serializable  */ {

    /**
     * @return bool
     */
    function isEmpty();

}
