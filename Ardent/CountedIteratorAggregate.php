<?php

namespace Ardent;


final
class CountedIteratorAggregate implements \Countable, \IteratorAggregate {

	private $storage;

	public
	function __construct(\Iterator $iterable) {
		foreach ($iterable as $key => $value) {
			$this->storage[] = [$key, $value];
		}
	}

	public
	function count(): int {
		return \count($this->storage);
	}

	public
	function getIterator(): \Iterator {
		foreach ($this->storage as list($key, $value)) {
			yield $key => $value;
		}
	}

}

