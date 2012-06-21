<?php

namespace Spl;

/**
 * OverflowException should be thrown when an integer overflow is detected and cannot be immediately handled.  It should
 * also be thrown when an attempt is made to add more values to a full data structure.
 */
class OverflowException extends RangeException {

}
