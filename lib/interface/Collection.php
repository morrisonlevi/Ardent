<?php
namespace Spl;

/**
 * By Collection, we do not mean the same thing as a Java collection. It is
 * simply a name by which we can give generic properties to.
 */
interface Collection extends Countable, Iterator {
    function clear();
    function contains($item);
    function isEmpty();
}

?>
