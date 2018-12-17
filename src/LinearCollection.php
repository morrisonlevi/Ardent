<?php

namespace ardent {

	// should also be cloneable
	interface LinearCollection
		extends \ArrayAccess, Collection
	{
		// Remove items beginning at (inclusive) $ndx until the end
		//todo: name?
		function truncate(int $ndx): void;

		// Add items to the end of the linear collection.
		function append(iterable $xs): void;
	}

}

?>
