<?php
namespace Spl;

/**
 * By Collection, we do not mean the same thing as a Java collection. It is
 * simply a name by which we can give generic properties to.
 */
interface Collection extends \Countable, \Traversable {
    /**
     * Removes all of the elements from the collection.
     *
     * @return void
     */
    function clear();

    /**
     * Returns true if the collection contains the given item at least once.
     * 
     * @param mixed $item Item whose presence is to be tested.
     * @return bool Returns true if the collection contains the item.
     */
    function contains($item);

    /**
     * Returns true if this collection contains no items.  This is exactly
     * equivalent to testing count for 0.
     *
     * @return bool Returns true if the colleciton contains no items.
     */
    function isEmpty();

}

?>
