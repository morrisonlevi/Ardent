<?php

namespace Collections;

/**
 * StateExceptions should be used when performing an operation that is invalid
 * based on the current state.
 *
 * Known sub-classes:
 *  - EmptyException
 *  - FullException
 */
class StateException extends Exception {

}
