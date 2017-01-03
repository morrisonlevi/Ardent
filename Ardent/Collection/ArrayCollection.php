<?php

namespace Ardent\Collection;

use Ardent\Collection;
use Ardent\Enumerable;
use Ardent\EnumerableTrait;
use Ardent\Optional;


final
class ArrayCollection implements Collection, Enumerable {

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
	function choose(callable $f): ArrayCollection {
		$output = new ArrayCollection();
		foreach ($this->inner as $k => $v) {
			$opt = $f($v);
			assert($opt instanceof Optional);
			foreach ($opt as $w) {
				$output->inner[$k] = $w;
			}
		}
		return $output;
	}

}

