<?php

namespace ardent {

	use OutOfBoundsException;
	use TypeError;
	use UnexpectedValueException;

	final class Slice implements LinearCollection
	{
		private $data;
		private $begin, $end;

		function __construct(LinearCollection $data, int $begin, int $end) {
			assert(0 <= $begin && $begin <= $end);
			assert($end <= $data->count());
			$this->data = clone $data;
			$this->begin = $begin;
			$this->end = $end;
		}

		function append(iterable $xs): void {
			// todo: optimize this to avoid copies when possible
			if ($this->end == $this->data->count()) {
				// if we are pointing to the end already we can delegate
				$this->data->append($xs);
				$this->end = $this->data->count();
			} else {
				// if we are not pointing to the end then fix it up
				$vector = Vector::from($this);
				// (theoretically) free up memory before append
				$this->data = $vector;
				$vector->append($xs);

				$this->begin = 0;
				$this->end = $vector->count();
			}
		}

		function offsetExists($ndx): bool {
			assert(\is_int($ndx));
			return 0 <= $ndx && $ndx < $this->count();
		}

		function offsetGet($ndx) {
			assert(\is_int($ndx));
			assert($this->offsetExists($ndx));
			return $this->data[$ndx + $this->begin];
		}

		function offsetSet($ndx, $value): void {
			if ($ndx !== null) {
				assert(\is_int($ndx));
				assert($this->offsetExists($ndx));
				$this->data[$ndx + $this->begin] = $value;
			} else {
				assert(false && "Not yet implemented");
				$this->data[] = $value;
			}
		}

		function offsetUnset($ndx): void {
			assert(\is_int($ndx));
			if ($this->offsetExists($ndx)) {
				if ($ndx == $this->end - 1) {
					--$this->end;
				} else {
					assert(false && "Not yet implemented");
				}
			}
		}

		function truncate(int $ndx): void {
			assert($this->offsetExists($ndx));
			$this->end = $this->begin + $ndx;
		}

		function getIterator(): \Iterator {
			return LinearIterator::all($this);
		}

		function count(): int { return $this->end - $this->begin; }
	}

}

?>
