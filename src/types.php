<?php

namespace morrisonlevi\ardent {

	interface type_t {
		function accepts($value): bool;
	}


	function basic_type_name_of($value) {
		$basic_type = \gettype($value);
		switch ($basic_type) {

			case 'boolean':
				return 'bool';
			case 'integer':
				return 'int';
			case 'double':
				return 'float';

			case 'object':
				return \get_class($value);

			case 'NULL':
				return 'null';
			case 'string':
			case 'array':
				return $basic_type;

			case 'resource':
			case 'resource (closed)':
			case 'unknown type':
			default:
				throw new \TypeError("Type \"$basic_type\" unsupported");
		}
	}


	final class bool_t implements type_t {
		function accepts($value): bool {
			return \is_bool($value);
		}
	}


	final class int_t implements type_t {
		function accepts($value): bool {
			return \is_int($value);
		}
	}


	final class float_t implements type_t {
		function accepts($value): bool {
			return \is_float($value);
		}
	}


	final class string_t implements type_t {
		function accepts($value): bool {
			return \is_string($value);
		}
	}


	final class nullable_t implements type_t {
		private $inner_type;

		private function __construct(type_t $inner_type) {
			$this->inner_type = $inner_type;
		}

		static function of(type_t $type) {
			return new self($type);
		}

		function accepts($value): bool {
			return \is_null($value) || $this->inner_type->accepts($value);
		}
	}

	final class class_t implements type_t {
		private $classname;
		private function __construct(string $classname) {
			$this->classname = $classname;
		}

		static function of(string $type) {
			assert(\class_exists($type) || \interface_exists($type), new \TypeError());
			return new self($type);
		}

		function accepts($value): bool {
			return \is_object($value) && $value instanceof $this->classname;
		}
	}


	final class array_t implements type_t {
		private $inner_type;

		private function __construct(type_t $inner_type) {
			$this->inner_type = $inner_type;
		}

		static function of(type_t $type) {
			return new self($type);
		}

		private function all_members_are_correct_type($array): bool {
			foreach ($array as $value) {
				if (!$this->inner_type->accepts($value)) {
					return false;
				}
			}
			return true;
		}

		function accepts($value): bool {
			return \is_array($value) && $this->all_members_are_correct_type($value);
		}
	}


	/* I don't particularly like having functions that just construct something
	 * of the same name but done for UX. */
	function bool_t() { return new bool_t(); }
	function int_t() { return new int_t(); }
	function float_t() { return new float_t(); }
	function string_t() { return new string_t(); }
	function nullable_t(type_t $t) { return nullable_t::of($t); }
	function array_t(type_t $t) { return array_t::of($t); }
	function class_t(string $name) { return class_t::of($name); };

}

?>