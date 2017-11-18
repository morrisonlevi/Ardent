<?php

namespace Ardent {

	trait Sequence {

		abstract function getIterator(): \Iterator;

		function map(callable $fn): MappingSequence {
			return new MappingSequence($fn, $this->getIterator());
		}

		function filter(callable $fn): FilteringSequence {
			return new FilteringSequence($fn, $this->getIterator());
		}

		function reduce($initial, callable $fn) {
			$result = $initial;
			foreach ($this as $value) {
				$result = $fn($result, $value);
			}
			return $result;
		}

		function reduce1(callable $fn) {
			$it = $this->getIterator();
			if (!$it->valid()) {
				// todo: choose specific exception
				throw new Exception();
			}

			$result = $it->current();
			for ($it->next(); $it->valid(); $it->next()) {
				$result = $fn($result, $it->current());
			}
			return $result;
		}

		function keys(): KeysSequence {
			return new KeysSequence($this->getIterator());
		}

		function values(): ValuesSequence {
			return new ValuesSequence($this->getIterator());
		}

	}

}

?>
