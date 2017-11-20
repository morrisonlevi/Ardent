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

		private function find_matching() {
			while ($this->inner->valid()) {
				$this->item = $this->inner->current();
				if (($this->filtering_function)($this->item)) {
					break;
				}
				$this->inner->next();
			}
		}

		function rewind() {
			$this->inner->rewind();
			$this->find_matching();
		}

		function next(): void {
			$this->inner->next();
			$this->find_matching();
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