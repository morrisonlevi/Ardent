<?php

namespace Ardent\Algorithm;

function to_iterator($iterable): \Iterator {
	if (\is_array($iterable)) {
		return new \ArrayIterator($iterable);
	} else if ($iterable instanceof \Iterator) {
		return $iterable;
	} else {
		assert($iterable instanceof \Traversable);
		return new \IteratorIterator($iterable);
	}
}

