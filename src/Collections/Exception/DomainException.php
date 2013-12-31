<?php

namespace Collections;

/**
 * DomainException should be thrown when a value is outside of a specific
 * domain. It should not be used when the type of the value is incompatible;
 * TypeException or a sub-class of it should be used instead.
 */
class DomainException extends Exception {

}
