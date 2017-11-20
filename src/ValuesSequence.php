<?php

namespace Ardent {

	final class ValuesSequence implements \OuterIterator {

		use IotaIterator;
		use Sequence;

		function current() {
			return $this->inner->current();
		}

	}

}

?>