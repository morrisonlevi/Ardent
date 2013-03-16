<?php

namespace Ardent\Exception;

/**
 * EmptyException should be used when performing an operation that is not
 * allowed on an empty structure, such as popping an item from an empty stack.
 */
class EmptyException extends StateException {

}
