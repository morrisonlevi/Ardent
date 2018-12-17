<?php

namespace ardent {

	use UnexpectedValueException;

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

}

?>