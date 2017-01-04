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
	 * Applies $f to every element, adding the values from the Optionals
	 * when present.
	 * @param callable $f that returns an \Ardent\Optional
	 * @return Enumerable
	 */
	function choose(callable $f);

	/**
	 * @param callable $f
	 * @return Enumerable
	 */
	function filter(callable $f);

	function flatten();

	function fold(callable $f, $initial);

	function isEmpty(): bool;

	/**
	 * @param int $n
	 * @return Enumerable
	 */
	function limit(int $n);

	/**
	 * @param callable $f
	 * @return Enumerable
	 */
	function map(callable $f);

	function reduce(callable $f): Optional;

	/**
	 * @param int $n
	 * @return Enumerable
	 */
	function skip(int $n);

	function to(Collection\Builder $builder);

	function toArray(): array;

}

