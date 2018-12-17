<?php

namespace ardent {

	use OutOfBoundsException;
	use TypeError;
	use UnexpectedValueException;

	function to_iterator(iterable $iter): \Iterator {
		if (\is_array($iter)) return new \ArrayIterator($iter);
		else if ($iter instanceof \Iterator) return $iter;
		else return new \IteratorIterator($iter);
	}

	function to_array(iterable $iter) {
		if (\is_array($iter)) return \array_values($iter);
		else return \iterator_to_array($iter, $preserve_keys = false);
	}

	function zip(iterable ... $ins): \Iterator {
		if (empty($ins)) return;

		$iters = [];
		foreach ($ins as $in) {
			$iter = to_iterator($in);
			$iter->rewind();
			$iters[] = $iter;
		}

		for (;;) {
			$keys = []; $currents = [];
			foreach ($iters as $iter) {
				if (!$iter->valid()) return;
				$keys[] = $iter->key();
				$currents[] = $iter->current();
				$iter->next();
			}
			yield $keys => $currents;
		}
	}

	/*
	 * Returns an Iterator consisting of the result of apply $mapper to
	 * set of first items of each $ins, followed by applying $mapper
	 * to the set of second items in each $ins, and so on until any
	 * one of the $ins is exhausted. The remaining items are ignored.
	 * The $mapper should accept count(\$ins) number of arguments.
	 */
	function map(callable $mapper, iterable ... $ins): \Iterator {
		foreach (zip(... $ins) as $vals) yield $mapper(... $vals);
	}

	function map1(callable $mapper, iterable $in): \Iterator {
		foreach ($in as $key => $val) yield $key => $mapper($val);
	}

	function for_each(callable $fn, iterable ... $ins): void {
		foreach (zip(... $ins) as $vals) $fn(... $vals);
	}

	function filter(callable $fn, iterable $in) {
		foreach ($in as $key => $val) if ($fn($val)) yield $key => $val;
	}

	function fold($initial, callable $combine, iterable $in) {
		$result = $initial;
		foreach ($in as $val) $result = $combine($result, $val);
		return $result;
	}

	function all_of(callable $fn, iterable $in): bool {
		foreach ($in as $val) if (!$fn($val)) return false;
		return true;
	}

	function any_of(callable $fn, iterable $in): bool {
		foreach ($in as $val) if ($fn($val)) return true;
		return false;
	}

	function none_of(callable $fn, iterable $in): bool {
		foreach ($in as $val) if ($fn($val)) return false;
		return true;
	}


	interface Collection
		extends \Countable, \IteratorAggregate
	{
		function count(): int;

		/* Must return an Iterator -- no goofy non-iterator traversables
		 * Please ensure count($this) == \iterator_count($this->getIterator());
		 */
		function getIterator(): \Iterator;
	}

	// should also be cloneable
	interface LinearCollection
		extends \ArrayAccess, Collection
	{
		// Remove items beginning at (inclusive) $ndx until the end
		//todo: name?
		function truncate(int $ndx): void;

		// Add items to the end of the linear collection.
		function append(iterable $xs): void;
	}

	/* Unsure about this one:
	 * final class ArrayObject implements LinearCollection;
	 * Since PHP arrays do not have to be linear
	 */

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
			assert(\is_int($ndx), new TypeError());
			return 0 <= $ndx && $ndx < $this->count();
		}

		function offsetGet($ndx) {
			assert(\is_int($ndx), new TypeError());
			assert($this->offsetExists($ndx), new OutOfBoundsException());
			return $this->data[$ndx];
		}

		function offsetSet($ndx, $value): void {
			if ($ndx !== null) {
				assert(\is_int($ndx), new TypeError());
				assert($this->offsetExists($ndx), new OutOfBoundsException());
				$this->data[$ndx] = $value;
			} else {
				$this->data[] = $value;
			}
		}

		function offsetUnset($ndx): void {
			assert(\is_int($ndx), new TypeError());
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
			assert($this->offsetExists($ndx), new \OutOfBoundsException());
			// todo: ensure this doesn't have un-intended effects
			\array_splice($this->data, $ndx);
		}

		function getIterator(): \Iterator {
			return LinearIterator::all($this);
		}

		function count(): int { return \count($this->data); }
	}

	final class Slice implements LinearCollection
	{
		private $data;
		private $begin, $end;

		function __construct(LinearCollection $data, int $begin, int $end) {
			assert(0 <= $begin && $begin <= $end, new UnexpectedValueException());
			assert($end <= $data->count(), new UnexpectedValueException());
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
			assert(\is_int($ndx), new TypeError());
			return 0 <= $ndx && $ndx < $this->count();
		}

		function offsetGet($ndx) {
			assert(\is_int($ndx), new TypeError());
			assert($this->offsetExists($ndx), new OutOfBoundsException());
			return $this->data[$ndx + $this->begin];
		}

		function offsetSet($ndx, $value): void {
			if ($ndx !== null) {
				assert(\is_int($ndx), new TypeError());
				assert($this->offsetExists($ndx), new OutOfBoundsException());
				$this->data[$ndx + $this->begin] = $value;
			} else {
				assert(false, new \RuntimeException("Not yet implemented"));
				$this->data[] = $value;
			}
		}

		function offsetUnset($ndx): void {
			assert(\is_int($ndx), new TypeError());
			if ($this->offsetExists($ndx)) {
				if ($ndx == $this->end - 1) {
					--$this->end;
				} else {
					assert(false, new \RuntimeException("Not yet implemented"));
				}
			}
		}

		function truncate(int $ndx): void {
			assert($this->offsetExists($ndx), new OutOfBoundsException());
			$this->end = $this->begin + $ndx;
		}

		function getIterator(): \Iterator {
			return LinearIterator::all($this);
		}

		function count(): int { return $this->end - $this->begin; }
	}

	final class LinearIterator
		implements \Countable, \Iterator
	{
		private $data;
		private $begin, $end;
		private $ndx;

		function __construct(LinearCollection $data, int $begin, int $end) {
			assert(0 <= $begin && $begin <= $end, new UnexpectedValueException());
			assert($end <= $data->count(), new UnexpectedValueException());
			$this->data = clone $data;
			$this->begin = $begin;
			$this->end = $end;
			$this->ndx = $begin;
		}

		static function all(LinearCollection $data): LinearIterator {
			return new LinearIterator($data, 0, $data->count());
		}

		function count(): int { return $this->end - $this->begin; }
		function rewind(): void { $this->ndx = $this->begin; }
		function valid(): bool { return $this->ndx < $this->end; }
		function next(): void { ++$this->ndx; }
		function key(): int { return $this->ndx - $this->begin; }
		function current() { return $this->data[$this->ndx]; }
	}

	function unique(callable $eq, LinearCollection $data):void {
		if ($data->count() > 0) {
			$i = $begin = 0;
			$end = $data->count();

			while (++$begin != $end) {
				if (!$eq($data[$i], $data[$begin]) && ++$i != $begin) {
					$data[$i] = $data[$begin];
				}
			}

			$data->truncate(++$i);
		}
	}

	function eq($a, $b): bool { return $a == $b; }
	function neq($a, $b): bool { return $a != $b; }
	function cmp($a, $b): int { return $a <=> $b; }

}

namespace {
	$input = ardent\Vector::from([1, 1, 2, 3, 3]);
	$input2 = new ardent\Slice($input, 0, $input->count());
	ardent\unique('ardent\eq', $input2);
	var_export(\iterator_to_array($input)); echo "\n";
	var_export(\iterator_to_array($input2)); echo "\n";
}
