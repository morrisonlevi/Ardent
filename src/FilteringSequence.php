<?php

namespace Ardent {

	final class FilteringSequence implements \OuterIterator {

		private $inner;
		private $filtering_function;
		private $item;

		function __construct(callable $f, \Iterator $inner) {
			$this->inner = $inner;
			$this->filtering_function = $f;
		}

		use OuterIteratorTrait;
		use Sequence;

		function getInnerIterator(): \Iterator {
			return $this->inner;
		}

		function rewind() {
			$this->inner->rewind();
			if ($this->inner->valid()) {
				$this->item = $this->inner->current();
			}
		}

		function next(): void {
			for ($this->inner->next(); $this->inner->valid(); $this->inner->next()) {
				$this->item = $this->inner->current();
				if (($this->filtering_function)($this->item)) {
					break;
				}
			}
		}

		function current() {
			return $this->item;
		}

		function getIterator(): self {
			return $this;
		}
	}

}

?>