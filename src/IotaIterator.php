<?php

namespace Ardent {

	trait IotaIterator {

		use OuterIteratorTrait;

		private $offset = 0;
		protected $inner;

		function __construct(\Iterator $data) {
			$this->inner = $data;
		}

		function getIterator(): \Iterator {
			return $this;
		}

		function getInnerIterator(): \Iterator {
			return $this->inner;
		}

		function rewind() {
			$this->inner->rewind();
			$this->offset = 0;
		}

		function key() {
			return $this->offset;
		}

		function current() {
			return $this->offset;
		}

		function next(): void {
			$this->inner->next();
			$this->offset += 1;
		}
	}

}

?>
