<?php

namespace Spl;

/**
 * OverflowException should be thrown when an integer underflow is detected and cannot be immediately handled.  It
 * should also be thrown when an attempt is made to remove values from a data structure when it is not feasible to do
 * so. An example would be calling Stack::pop() on an empty Stack.
 */
class UnderflowException extends RangeException {

}
