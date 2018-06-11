<?php

namespace morrisonlevi\ardent {

	final class maybe/*<T>*/ {
		private $_type;
		private $has_data;
		private $data;

		function type_parameters(): array {
			return ['T'];
		}
		function type_arguments(): array {
			return [$this->_type];
		}

		private function __construct(type_t $t, bool $has_data, /*T*/$data) {
			$this->_type = $t;
			$this->has_data = $has_data;
			$this->data = $data;
		}

		static function of(type_t $t, /*T*/$data): maybe {
			assert($t->accepts($data), new \TypeError());
			return new self($t, true, $data);
		}

		static function empty(type_t $t): maybe {
			return new self($t, false, null);
		}

		function get() {
			assert($this->has_value(), new \Exception());
			assert($this->_type->accepts($this->data), new \TypeError());
			return $this->data;
		}

		function has_value(): bool {
			return $this->has_data;
		}

	}

}

?>