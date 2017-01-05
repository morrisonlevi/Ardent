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
	function filter(callable $f) {
		$output = new self();
		foreach ($this->inner as $key => $value) {
			if ($f($value)) {
				$output->inner[$key] = $value;
			}
		}
		return $output;
	}

	public
	function map(callable $f) {
		$output = new self();
		foreach ($this->inner as $key => $value) {
			$output->inner[$key] = $f($value);
		}
		return $output;
	}

	public
	function reduce(callable $f): \Ardent\Optional {
		if (!empty($this->inner)) {
			$carry = \array_shift($this->inner);
			foreach ($this->inner as $value) {
				$carry = $f($carry, $value);
			}
			return \Ardent\Optional::some($carry);
		} else {
			return \Ardent\Optional::none();
		}
	}

}

