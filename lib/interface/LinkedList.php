<?php

/**
 * An initial API for LinkedList.
 * 
 * @todo Decide on addAll and addArray etc.  Arrays don't implement Traversable.
 * @todo Decide on the proper exception to throw when an illegal index type is given.
 */
interface LinkedList extends Countable, ArrayAccess, Iterator {

    /**
     * Removes all the elements from the list.
     *
     * @return void
     */
    function clear();

    /**
     * Returns true if $item is contained at least once in this list.
     *
     * @param mixed $item The item to test for containment.
     * @return bool 
     */
    function contains($item);
    
    /**
     * Returns the element of this list at the specified postion.
     *
     * @param int $index The index to get.
     * @return mixed The item at the specified $index.
     * @throws OutOfBoundsException if the $index is out of bounds.
     * @throws an exception if $index is not an in.
     */
    function get($index);

    /**
     * Returns the index of the first occurrence of the item in the list, or a
     * negative number if it is not found.
     *
     * @param mixed $item The item to look for.
     * @return int The index of the item, or a negative number if not found.
     */
    function indexOf($item);

    /**
     * Returns the index of that last occurrence of the item in the list, or a
     * negative number if it is not found.
     *
     * @param mixed $item The item to look for.
     * @return int The index of the item, or a negative number if not found.
     */
    function lastIndexOf($item);

    /**
     * Removes and returns the last item in the list.  Will shorten the list
     * by one.
     *
     * @return mixed The last element in the list.
     * @throws UnderflowException if the list is empty.
     */
    function pop();

    /**
     * Appends the item to the end of the list.
     *
     * @param mixed $item The item to append.
     * @return void
     */
    function push($item);

    /**
     * Appends all of the items to the end of the list.  This is done in the
     * order that they are returned by the $items iterator.
     * 
     * @param Traversable $item The object containing the items to append.
     * @return void
     */
    function pushAll(Traversable $items);

    /**
     * Removes the item at the specified position in the list. Shifts the 
     * remaining elements to the left.  Returns the item removed.
     *
     * @param int $index The position to remove.
     * @return mixed The item removed.
     * @throws OutOfBoundsException if the provided $index is out of bounds.
     * @throws an exception if $index is not an integer.
     */
    function remove($index);

    /**
     * Removes the first occurrence of the item in the list. If the list does
     * not contain the item, the list is unchanged.
     *
     * @return void
     */
    function removeItem($item);

    /**
     * Removes the last occurrence of the item in the list.  If the list does
     * not contain the item, the list is unchanged.
     *
     * @return void
     */
    function removeLastItem($item);

    /**
     * Replaces the item at the specified index with the specified item.
     * 
     * @param int $index
     * @param mixed item
     * @return mixed The item previously at the specified index.
     * @throws an exception if $index is not an int.
     * @throws OutOfBoundsException if the index is out of bounds.
     */
    function set($index, $item);

    /**
     * Removes and returns the first item in the list.
     *
     * @return mixed The first item in the list.
     * @throws UnderflowException if the list is empty.
     */
    function shift();

    /**
     * Inserts the item at the beginning of the list.
     *
     * @param mixed $item The item to insert.
     * @return mixed The first item in the list.
     */
    function unshift($item);

    /**
     * Inserts all of the items to the beginning of the list. This is done in
     * the order that they are returned by the $items iterator.
     *
     * @returns void
     */
    function unshiftAll(Traversable $items);

}

?>
