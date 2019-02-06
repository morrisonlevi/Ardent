<?php

namespace ardent {

	final class Vector implements LinearCollection
	{
		private $data = [];

		function append(iterable $xs): void {
			foreach ($xs as $x) $this->data[] = $x;
		}

		static function from(iterable $xs): Vector {
			$vec = new Vector();
			$vec->append($xs);
			return $vec;
		}

		function offsetExists($ndx): bool {
			assert(\is_int($ndx));
			return 0 <= $ndx && $ndx < $this->count();
		}

		function offsetGet($ndx) {
			assert(\is_int($ndx));
			assert($this->offsetExists($ndx));
			return $this->data[$ndx];
		}

		function offsetSet($ndx, $value): void {
			if ($ndx !== null) {
				assert(\is_int($ndx));
				assert($this->offsetExists($ndx));
				$this->data[$ndx] = $value;
			} else {
				$this->data[] = $value;
			}
		}

		function offsetUnset($ndx): void {
			assert(\is_int($ndx));
			if ($this->offsetExists($ndx)) {
				// Do not allow holes!
				$end = $this->count() - 1;
				for ($i = $ndx; $i < $end; ++$i) {
					$this->data[$i] = $this->data[$i + 1];
				}
				unset($this->data[$i]);
			}
		}

		function truncate(int $ndx): void {
			assert($this->offsetExists($ndx));
			// todo: ensure this doesn't have un-intended effects
			\array_splice($this->data, $ndx);
		}

		function getIterator(): \Iterator {
			return LinearIterator::all($this);
		}

		function count(): int { return \count($this->data); }
	}

}

?>
