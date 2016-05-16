<?php

namespace Ardent;

use Countable;
use IteratorAggregate;


final
class Option implements Countable, IteratorAggregate {

	private $has_value;
	private $value;

	private
	function __construct($has_value, $value) {
		$this->has_value = $has_value;
		$this->value = $value;
	}

	public static
	function some($thing) {
		return new Option(true, $thing);
	}

	public static
	function none() {
		static $none = null;
		if ($none === null) {
			$none = new Option(false, null);
		}
		return $none;
	}

	public static
	function fromMaybeNull($value) {
		return ($value !== null)
			? Option::some($value)
			: Option::none();
	}

	public static
	function fromMaybeFalse($value) {
		return ($value !== false)
			? Option::some($value)
			: Option::none();
	}

	public static
	function fromPredicate(callable $f, $value) {
		return $f($value)
			? Option::some($value)
			: Option::none();
	}

	public
	function count() {
		return ($this->has_value ? 1 : 0);
	}

	public
	function filter(callable $f) {
		return ($this->has_value && $f($this->value))
			? $this
			: Option::none();
	}

	public
	function getIterator() {
		if ($this->has_value) {
			yield $this->value;
		}
	}

	public
	function map(callable $f) {
		return ($this->has_value)
			? Option::some($f($this->value))
			: Option::none();
	}

	public
	function match(callable $ifSome, callable $ifNone) {
		return ($this->has_value)
			? $ifSome($this->value)
			: $ifNone();
	}

}

