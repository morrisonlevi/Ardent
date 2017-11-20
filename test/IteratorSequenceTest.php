<?php

namespace Ardent {

	use PHPUnit\Framework\TestCase;

	class IteratorSequenceTest extends TestCase {

		static function createFromArray(array $data) {
			return new IteratorSequence(new \ArrayIterator($data));
		}

		function test_empty_count() {
			$seq = self::createFromArray([]);
			self::assertEquals(0, \iterator_count($seq));
		}

		function test_keys() {
			$seq = self::createFromArray([1 => 0, 3 => 1]);
			$output = $seq->keys();
			$expect = [1, 3];
			$actual = \iterator_to_array($output);
			self::assertEquals($expect, $actual);
		}

		function test_values() {
			$seq = self::createFromArray([1 => 0, 3 => 6]);
			$output = $seq->values();
			$expect = [0, 6];
			$actual = \iterator_to_array($output);
			self::assertEquals($expect, $actual);
		}

		function test_map() {
			$seq = self::createFromArray(\range(3, 5));
			$output = $seq->map(function ($x) {
				return $x + 1;
			});
			$expect = \range(4, 6);
			$actual = \iterator_to_array($output);
			self::assertEquals($expect, $actual);
		}

		function test_filter() {
			$data = \range(1, 3);
			$seq = self::createFromArray($data);
			$output = $seq->filter(function ($x) {
				return $x % 2 == 1;
			});
			$expect = [0 => 1, 2 => 3];
			$actual = \iterator_to_array($output);
			self::assertEquals($expect, $actual);
		}

		function test_reduce() {
			$seq = self::createFromArray([1 => 2, 3 => 6]);
			$actual = $seq->reduce(3, function ($result, $x) {
				return $result + $x;
			});
			$expect = 11;
			self::assertSame($expect, $actual);
		}

		function test_reduce1() {
			$seq = self::createFromArray([1 => 2, 3 => 6]);
			$actual = $seq->reduce1(function ($result, $x) {
				return $result + $x;
			});
			$expect = 8;
			self::assertSame($expect, $actual);
		}
	}

}