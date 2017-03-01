<?php

namespace Ardent;


trait EnumerableTrait {

	abstract
	function newBuilder(): Collection\Builder;

	abstract
	function getIterator(): \Iterator;

	private
	function build(callable $algorithm, ... $args) {
		$bldr = $this->newBuilder();
		foreach ($algorithm(... $args) as $key => $value) {
			$bldr->add($key, $value);
		}
		return $bldr->result();
	}

	public
	function choose(callable $f) {
		return $this->build('Ardent\Algorithm\choose', $f, $this->getIterator());
	}

	public
	function filter(callable $f) {
		return $this->build('Ardent\Algorithm\filter', $f, $this->getIterator());
	}

	public
	function flatten() {
		return $this->build('Ardent\Algorithm\flatten', $this->getIterator());
	}

	public
	function limit(int $n) {
		return $this->build('Ardent\Algorithm\limit', $n, $this->getIterator());
	}

	public
	function map(callable $f) {
		return $this->build('Ardent\Algorithm\map', $f, $this->getIterator());
	}

	public
	function skip(int $n) {
		return $this->build('Ardent\Algorithm\skip', $n, $this->getIterator());
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
	function toSequentialArray(): array {
		return \iterator_to_array($this->getIterator(), $preserve_keys = false);
	}

	public
	function isEmpty(): bool {
		$iter = $this->getIterator();
		$iter->rewind();
		return $iter->valid();
	}

}

