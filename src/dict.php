<?php

namespace morrisonlevi\ardent {

	final class dict/*<Key,Value>*/ implements \ArrayAccess, \Countable {

		private $_types;
		private $hash;
		private $eq;

		private $keys;
		private $values;
		private $_count;

		private function __construct(type_t $key, type_t $value, callable $hasher, callable $equater) {
			$this->_types = [$key, $value];
			$this->hash = $hasher;
			$this->eq = $equater;
			$this->keys = [];
			$this->values = [];
			$this->_count = 0;
		}
		
		static function of(type_t $key, type_t $value, ?callable $hash_fn = null, ?callable $eq_fn = null) {
			assert(!$key->accepts(null));
			if ($hash_fn === null) {
				$hash_fn = __NAMESPACE__ . '\hash';
			}
			if ($eq_fn === null) {
				$eq_fn = __NAMESPACE__ . '\eq';
			}
			return new self($key, $value, $hash_fn, $eq_fn);
		}

		function type_parameters(): array {
			return ['Key', 'Value'];
		}

		function type_arguments(): array {
			return $this->_types;
		}

		private function find(/*Key*/$key) {
			$hash = ($this->hash)($key);
			$keys = $this->keys[$hash] ?? null;
			if ($keys === null) {
				return null;
			}
			$eq = $this->eq;
			foreach ($keys as $offset => $k) {
				if ($eq($k, $key)) {
					return [$hash, $offset];
				}
			}
			return null;
		}

		function offset_exists(/*Key*/ $key): bool {
			list($Key,) = $this->_types;
			assert($Key->accepts($key), new \TypeError());
			return $this->find($key) !== null;
		}

		function offsetExists($offset): bool {
			return $this->offset_exists($offset);
		}

		function offset_get($offset) {
			list($Key,) = $this->_types;
			assert($Key->accepts($offset), new \TypeError());
			list($hash, $key) = $this->find($offset);
			if ($key === null) {
				throw new \OutOfBoundsException();
			}

			return $this->values[$hash][$key];
		}

		function offsetGet($offset) {
			return $this->offset_get($offset);
		}

		function offset_set($key, $value): void {
			list($Key, $Value) = $this->_types;
			assert($Key->accepts($key), new \TypeError());
			assert($Value->accepts($value), new \TypeError());


			$hash = ($this->hash)($key);
			$keys = $this->keys[$hash] ?? null;
			if ($keys === null) {
				$this->keys[$hash] = [];
				$this->values[$hash] = [];
			} else {
				$eq = $this->eq;
				foreach ($keys as $offset => $k) {
					if ($eq($k, $key)) {
						$this->keys[$hash][$offset] = $key;
						$this->values[$hash][$offset] = $value;
						return;
					}
				}
			}
			$this->keys[$hash][] = $key;
			$this->values[$hash][] = $value;
			$this->_count += 1;
		}

		function offsetSet(/*Key*/$key, /*Value*/ $value): void {
			if ($key === null) {
				throw new \OutOfBoundsException();
			}
			$this->offset_set($key, $value);
		}

		function offset_unset(/*Key*/ $key) {
			list($Key,) = $this->_types;
			assert($Key->accepts($key), new \TypeError());

			$hash = ($this->hash)($key);
			$keys = $this->keys[$hash] ?? null;
			if ($keys !== null) {
				$eq = $this->eq;
				foreach ($keys as $offset => $k) {
					if ($eq($k, $key)) {
						\array_splice($this->keys[$hash], $offset, 1);
						\array_splice($this->values[$hash], $offset, 1);
						$this->_count -= 1;
					}
				}
			}
		}

		function offsetUnset($key) {
			$this->offset_unset($key);
		}

		function count(): int {
			return $this->_count;
		}

	}

}

?>