<?php
namespace Spl;

use Exception;

/**
 * DomainException should be used when a value is given outside of a specific domain.  It should not be used when the
 * type of the value is incompatible; InvalidTypeException or a sub-class of it should be used instead.
 */
class DomainException extends Exception {

}
