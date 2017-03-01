<?php

namespace Ardent\Collection;

use Ardent\Enumerable;
use Ardent\EnumerableTrait;


final
class ArrayCollection implements Enumerable {

	use EnumerableTrait;

	private $inner;

	public
	function __construct(array $array = []) {
		$this->inner = $array;
	}

	public
	function newBuilder(): Builder {
		return new class() implements Builder {

			private $inner = [];
			function add($key, $value) {
				$this->inner[$key] = $value;
			}

			function result(): ArrayCollection {
				return new ArrayCollection($this->inner);
			}
		};
	}

	public
	function getIterator(): \Iterator {
		return new \ArrayIterator($this->inner);
	}

	public
	function count(): int {
		return \count($this->inner);
	}


}

