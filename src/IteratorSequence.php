<?php

namespace Ardent {

	trait IteratorSequence {

		use OuterIteratorTrait;
		use Sequence;

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

	}

}

?>