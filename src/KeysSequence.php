<?php

namespace Ardent {

	final class KeysSequence implements \OuterIterator {

		use IotaIterator;
		use Sequence;

		function current() {
			return $this->inner->key();
		}

	}

}

?>
