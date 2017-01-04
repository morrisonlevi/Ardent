<?php

namespace Ardent;


/**
 * Many of these methods are required to return Enumerable but are not
 * enforced by return types. This is to allow implementors to provide a
 * stronger subtype if possible, and are encouraged to do so.
 * @todo Revisit above if/when covariant return types are available
 */
interface Enumerable extends \IteratorAggregate {

	function getIterator(): \Iterator;

	/**
	 * @param callable $f
	 * @return Enumerable
	 */
	function filter(callable $f);

	/**
	 * @param callable $f
	 * @return Enumerable
	 */
	function map(callable $f);

	/**
	 * @param int $n
	 * @return Enumerable
	 */
	function skip(int $n);

	/**
	 * @param int $n
	 * @return Enumerable
	 */
	function limit(int $n);

	function fold(callable $f, $initial);

	function reduce(callable $f): Optional;

	function to(Collection\Builder $builder);

	function toArray(): array;

	/**
	 * Applies $f to every element, adding the values from the Optionals
	 * when present.
	 * @param callable $f that returns an \Ardent\Optional
	 * @return Enumerable
	 */
	function choose(callable $f);

	function isEmpty(): bool;

}

