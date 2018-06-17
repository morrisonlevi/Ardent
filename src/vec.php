<?php

namespace morrisonlevi\ardent {

	final class vec/*<T>*/ implements \ArrayAccess, \Countable, \IteratorAggregate {
		private $_type;
		private $data;

		const T = 0;

		private function __construct(type_t $type) {
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
				assert($this->_type->accepts($value), new \TypeError());
				$this->data[] = $value;
			} else  {
				$this->offset_set($offset, $value);
			}

		}

		function offsetUnset($offset) {
			$this->offset_unset($offset);
		}

		static function of(type_t $type) {
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

		function append(iterable $iterable): void {
			$type_error = new \TypeError();
			foreach ($iterable as $value) {
				assert($this->_type->accepts($value), $type_error);
				$this->data[] = $value;
			}
		}

		function count(): int {
			return \count($this->data);
		}

		function getIterator(): value_iterable {
			return value_iterable::of($this->_type, $this->data);
		}

	}

}

?>