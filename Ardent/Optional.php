<?php

namespace Ardent;


final
class Optional implements Enumerable {

	private $has_value;
	private $value;

	private
	function __construct($has_value, $value) {
		$this->has_value = $has_value;
		$this->value = $value;
	}

	public static
	function some($thing) {
		return new Optional(true, $thing);
	}

	public static
	function none() {
		static $none = null;
		if ($none === null) {
			$none = new Optional(false, null);
		}
		return $none;
	}

	public static
	function fromMaybeNull($value) {
		return ($value !== null)
			? Optional::some($value)
			: Optional::none();
	}

	public static
	function fromMaybeFalse($value) {
		return ($value !== false)
			? Optional::some($value)
			: Optional::none();
	}

	public static
	function fromPredicate(callable $f, $value) {
		return $f($value)
			? Optional::some($value)
			: Optional::none();
	}

	public
	function count() {
		return ($this->has_value ? 1 : 0);
	}

	public
	function filter(callable $f) {
		return ($this->has_value && $f($this->value))
			? $this
			: Optional::none();
	}

	public
	function flatten() {
		if ($this->has_value) {
			assert($this->value instanceof Optional);
			return $this->value;
		} else {
			return $this;
		}
	}

	public
	function getIterator(): \Iterator {
		if ($this->has_value) {
			yield $this->value;
		}
	}

	public
	function map(callable $f) {
		return ($this->has_value)
			? Optional::some($f($this->value))
			: Optional::none();
	}

	public
	function match(callable $ifSome, callable $ifNone) {
		return ($this->has_value)
			? $ifSome($this->value)
			: $ifNone();
	}

	public
	function unwrap() {
		if (!$this->has_value) {
			throw new \RuntimeException();
		}

		return $this->value;
	}

	public
	function reduce(callable $f): Optional {
		return $this;
	}

	public
	function fold(callable $f, $initial) {
		if ($this->has_value) {
			return $f($initial, $this->value);
		} else {
			return $initial;
		}
	}

    public
    function skip(int $n) {
        if ($n <= 0) {
            return $this;
        } else {
            return Optional::none();
        }
    }

    public
    function limit(int $n) {
        if ($n < 1) {
            return Optional::none();
        } else {
            return $this;
        }
    }

    public
    function to(Collection\Builder $builder) {
        if ($this->has_value) {
            $builder->add(0, $this->value);
        }
        return $builder->result();
    }

    public
    function toArray(): array {
        if ($this->has_value) {
            return [$this->value];
        } else {
            return [];
        }
    }

    public
    function choose(callable $f) {
        if ($this->has_value) {
            $opt = $f($this->value);
            assert($opt instanceof self);
            return $opt;
        } else {
            return $this;
        }
    }

    public
	function isEmpty(): bool {
		return !$this->has_value;
	}

}

