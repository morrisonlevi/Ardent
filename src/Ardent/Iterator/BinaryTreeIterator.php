<?php

namespace Ardent\Iterator;

/**
 * The BinaryTreeIterators DO NOT call rewind in their constructors for
 * performance reasons; this is unlike most iterators in the library.
 */
interface BinaryTreeIterator extends CountableIterator, \Ardent\Collection {

}
