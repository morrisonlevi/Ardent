<?php

namespace morrisonlevi\ardent {

	final class vec/*<T>*/ implements \ArrayAccess, \Countable, \IteratorAggregate, has_iterator {
		private $_type;
		private $data;

		const T = 0;

		private function __construct(_type $type) {
			$this->_type = $type;
			$this->data = [];
		}

		function offsetExists($offset) {
			return $this->offset_exists($offset);
		}

		function offsetGet($offset) {
			return $this->offset_get($offset);
		}

		function offsetSet($offset, $value) {
			if ($offset === null) {
				$this->append($value);
			} else  {
				$this->offset_set($offset, $value);
			}

		}

		function offsetUnset($offset) {
			$this->offset_unset($offset);
		}

		static function of(_type $type) {
			return new self($type);
		}

		function type_parameters(): array {
			return ['T'];
		}

		function type_arguments(): array/*<_type>*/ {
			return [$this->_type];
		}

		function offset_exists(int $ndx): bool {
			return \array_key_exists($ndx, $this->data);
		}

		function offset_get(int $ndx)/*: T*/ {
			if (!$this->offset_exists($ndx)) {
				throw new \OutOfBoundsException ();
			}
			$item = $this->data[$ndx];
			assert($this->_type->accepts($item));
			return $item;
		}

		function offset_set(int $ndx, /*T*/ $value) {
			if (!$this->offset_exists($ndx)) {
				throw new \OutOfBoundsException();
			}
			assert($this->_type->accepts($value), new \TypeError());
			$this->data[$ndx] = $value;
		}

		function offset_unset(int $ndx) {
			assert($this->_type->accepts(null), new \LogicException(__METHOD__ . ' of this type does not support offsetUnset'));
			$this->offset_set($ndx, null);
		}

		function append(/*T*/ ... $values): void {
			assert(_array::of($this->_type)->accepts($values), new \TypeError());
			$this->data = \array_merge($this->data, $values);
		}

		function count(): int {
			return \count($this->data);
		}

		function get_iterator(): iterator/*<T>*/ {
			return new vec_iterator($this, 0, \count($this->data));
		}

		function getIterator(): \Iterator {
			return adapt_iterator($this->get_iterator());
		}

	}


	final class vec_iterator implements iterator	{
		private $vec;
		private $begin;
		private $offset;
		private $end;

		function __construct(vec $vec, int $begin, int $end) {
			$this->vec = $vec;
			$this->begin = $begin; // just for debugging purposes
			$this->offset = $begin;
			$this->end = $end;

			// todo: support negative ranges?
			assert($begin >= 0);
			assert($begin <= $end);
		}

		function next(): maybe {
			$type = $this->vec->type_arguments()[0];
			$offset_exists = $this->vec->offset_exists($this->offset);
			if ($offset_exists && $this->offset < $this->end) {
				return maybe::of($type, $this->vec->offset_get($this->offset++));
			} else {
				return maybe::empty($type);
			}
		}
	}


}

?>