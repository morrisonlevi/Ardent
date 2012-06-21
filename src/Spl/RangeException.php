<?php

namespace Spl;

use Exception;

/**
 * RangeException should be thrown when a value is outside the range of some scope. A more specific exception may be
 * thrown if it is more appropriate.
 *
 * Known sub-classes:
 * - OutOfBoundsException
 * - OverflowException
 * - UnderflowException
 */
class RangeException extends Exception {

}
