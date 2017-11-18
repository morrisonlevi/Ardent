<?php

namespace Ardent {
	final class MappingSequence implements \OuterIterator {

		private $inner;
		private $mapping_function;

		function __construct(callable $f, \Iterator $inner) {
			$this->inner = $inner;
			$this->mapping_function = $f;
		}

		use OuterIteratorTrait;
		use Sequence;

		function getInnerIterator(): \Iterator {
			return $this->inner;
		}

		function current() {
			return ($this->mapping_function)($inner->current());
		}

		function getIterator(): self {
			return $this;
		}
	}

}

?>
