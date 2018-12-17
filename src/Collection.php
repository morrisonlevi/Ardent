<?php

namespace ardent {

	interface Collection
		extends \Countable, \IteratorAggregate
	{
		function count(): int;

		/* Must return an Iterator -- no goofy non-iterator traversables
		 * Please ensure count($this) == \iterator_count($this->getIterator());
		 */
		function getIterator(): \Iterator;
	}

}

?>