<?php

namespace Ardent;

use Ardent\Collection\Builder;


trait EnumerableTrait {

	abstract
	function newBuilder(): Builder;

	abstract
	function getIterator(): \Iterator;

	public
	function filter(callable $f) {
		$bldr = $this->newBuilder();
		foreach ($this->getIterator() as $key => $value) {
			if ($f($value)) {
				$bldr->add($key, $value);
			}
		}
		return $bldr->result();
	}

	public
	function map(callable $f) {
		$bldr = $this->newBuilder();
		foreach ($this->getIterator() as $key => $value) {
			$bldr->add($key, $f($value));
		}
		return $bldr->result();
	}

	public
	function skip(int $n) {
		$bldr = $this->newBuilder();
		$iter = $this->getIterator();
		$i = 0;

		$iter->rewind();
		while ($iter->valid() && $i++ < $n) {
			$iter->next();
		}

		while ($iter->valid()) {
			$bldr->add($iter->key(), $iter->current());
			$iter->next();
		}

		return $bldr->result();
	}

	public
	function limit(int $n) {
		$bldr = $this->newBuilder();
		$iter = $this->getIterator();
		$i = 0;

		$iter->rewind();
		while ($iter->valid() && $i++ < $n) {
			$bldr->add($iter->key(), $iter->current());
			$iter->next();
		}

		return $bldr->result();
	}

	public
	function fold(callable $f, $initial) {
		return Algorithm\fold($f, $initial, $this->getIterator());
	}

	public
	function reduce(callable $f): Optional {
		$iter = $this->getIterator();
		$iter->rewind();

		if ($iter->valid()) {
			$carry = $iter->current();
			for ($iter->next(); $iter->valid(); $iter->next()) {
				$carry = $f($carry, $iter->current());
			}
			return Optional::some($carry);

		} else {
			return Optional::none();
		}
	}

	public
	function to(Collection\Builder $builder) {
		foreach ($this->getIterator() as $key => $value) {
			$builder->add($key, $value);
		}
		return $builder->result();
	}

	public
	function toArray(): array {
		return \iterator_to_array($this->getIterator(), $preserve_keys = true);
	}

	public
	function choose(callable $f) {
		$bldr = $this->newBuilder();
		foreach ($this->getIterator() as $k => $v) {
			$opt = $f($v);
			assert($opt instanceof Optional);
			foreach ($opt as $w) {
				$bldr->add($k, $w);
			}
		}
		return $bldr->result();
	}

	public
	function isEmpty(): bool {
		$iter = $this->getIterator();
		$iter->rewind();
		return $iter->valid();
	}

}

