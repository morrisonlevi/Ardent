<?php

namespace Ardent {

	trait OuterIteratorTrait {
		 abstract function getInnerIterator(): \Iterator;

		 function rewind(): void {
			  $this->getInnerIterator()->rewind();
		 }

		 function valid(): bool {
			  return $this->getInnerIterator()->valid();
		 }

		 function key() {
			  return $this->getInnerIterator()->key();
		 }

		 function current() {
			  return $this->getInnerIterator()->current();
		 }

		 function next(): void {
			  $this->getInnerIterator()->next();
		 }

	}

}

?>