<?php

namespace ardent {

	function to_iterator(iterable $iter): \Iterator {
		if (\is_array($iter)) return new \ArrayIterator($iter);
		else if ($iter instanceof \Iterator) return $iter;
		else return new \IteratorIterator($iter);
	}

	function to_array(iterable $iter) {
		if (\is_array($iter)) return \array_values($iter);
		else return \iterator_to_array($iter, $preserve_keys = false);
	}

	function to_assoc_array(iterable $iter) {
		if (\is_array($iter)) return $iter;
		else return \iterator_to_array($iter, $preserve_keys = true);
	}

	function zip(iterable ... $ins): \Iterator {
		if (empty($ins)) return;

		$iters = [];
		foreach ($ins as $in) {
			$iter = to_iterator($in);
			$iter->rewind();
			$iters[] = $iter;
		}

		for (;;) {
			$keys = []; $currents = [];
			foreach ($iters as $iter) {
				if (!$iter->valid()) return;
				$keys[] = $iter->key();
				$currents[] = $iter->current();
				$iter->next();
			}
			yield $keys => $currents;
		}
	}

	/*
	 * Returns an Iterator consisting of the result of apply $mapper to
	 * set of first items of each $ins, followed by applying $mapper
	 * to the set of second items in each $ins, and so on until any
	 * one of the $ins is exhausted. The remaining items are ignored.
	 * The $mapper should accept count(\$ins) number of arguments.
	 */
	function map(callable $mapper, iterable ... $ins): \Iterator {
		foreach (zip(... $ins) as $vals) yield $mapper(... $vals);
	}

	function map1(callable $mapper, iterable $in): \Iterator {
		foreach ($in as $key => $val) yield $key => $mapper($val);
	}

	function for_each(callable $fn, iterable ... $ins): void {
		foreach (zip(... $ins) as $vals) $fn(... $vals);
	}

	function filter(callable $fn, iterable $in) {
		foreach ($in as $key => $val) if ($fn($val)) yield $key => $val;
	}

	function fold($initial, callable $combine, iterable $in) {
		$result = $initial;
		foreach ($in as $val) $result = $combine($result, $val);
		return $result;
	}

	function all_of(callable $fn, iterable $in): bool {
		foreach ($in as $val) if (!$fn($val)) return false;
		return true;
	}

	function any_of(callable $fn, iterable $in): bool {
		foreach ($in as $val) if ($fn($val)) return true;
		return false;
	}

	function none_of(callable $fn, iterable $in): bool {
		foreach ($in as $val) if ($fn($val)) return false;
		return true;
	}

	function unique(callable $eq, LinearCollection $data):void {
		if ($data->count() > 0) {
			$i = $begin = 0;
			$end = $data->count();

			while (++$begin != $end) {
				if (!$eq($data[$i], $data[$begin]) && ++$i != $begin) {
					$data[$i] = $data[$begin];
				}
			}

			$data->truncate(++$i);
		}
	}

	function eq($a, $b): bool { return $a == $b; }
	function neq($a, $b): bool { return $a != $b; }
	function cmp($a, $b): int { return $a <=> $b; }

}

namespace {
	require __DIR__ . '/Collection.php';
	require __DIR__ . '/LinearCollection.php';
	require __DIR__ . '/LinearIterator.php';
	require __DIR__ . '/Slice.php';
	require __DIR__ . '/Vector.php';
}

?>
