<?php

namespace Collections;

/**
 * FullException should be thrown when performing an operation that is not
 * allowed on a full structure, such as adding to a full stack.
 */
class FullException extends StateException {

}
