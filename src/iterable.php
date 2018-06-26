<?php

namespace morrisonlevi\ardent {


	function to_associative_array(iterable $iterable): array {
		return \is_array($iterable)
			? $iterable
			: \iterator_to_array($iterable, $use_keys = true);
	}


	function to_array(iterable $iterable): array {
		return \is_array($iterable)
			? \array_values($iterable)
			: \iterator_to_array($iterable, $use_keys = false);
	}


	final class keyed_iterable/*<Key, Value>*/ implements \IteratorAggregate {
		private $Key;
		private $Value;
		private $iterable;

		private function __construct(type_t $key, type_t $value, iterable $iterable) {
			$this->Key = $key;
			$this->Value = $value;
			$this->iterable = $iterable;
		}

		static function type_parameters() {
			return ['Key', 'Value'];
		}

		function type_arguments() {
			return [$this->Key, $this->Value];
		}

		static function of(type_t $key, type_t $value, iterable $iterable) {
			return new self($key, $value, $iterable);
		}

		function getIterator(): \Iterator {
			$Key = $this->Key;
			$Value = $this->Value;
			foreach ($this->iterable as $key => $value) {
				assert($Key->accepts($key), new \TypeError());
				assert($Value->accepts($value), new \TypeError());
				yield $key => $value;
			}
		}

	}


	final class value_iterable/*<T>*/ implements \IteratorAggregate {
		private $T;
		private $iterable;

		private function __construct(type_t $value, iterable $iterable) {
			$this->T = $value;
			$this->iterable = $iterable;
		}

		static function type_parameters() {
			return ['T'];
		}

		function type_arguments() {
			return [$this->T];
		}

		static function of(type_t $value, iterable $iterable) {
			return new self($value, $iterable);
		}

		function getIterator(): \Iterator {
			$T = $this->T;
			foreach ($this->iterable as $value) {
				assert($T->accepts($value), new \TypeError());
				yield $value;
			}
		}

	}

}

?>