<?php

namespace Ardent {

	use PHPUnit\Framework\TestCase;

	class ArraySequenceTest extends TestCase {

		function test_empty_count() {
			$seq = new ArraySequence([]);
			self::assertCount(0, $seq);
		}

		function test_map() {
			$seq = new ArraySequence(\range(0, 3));
			$output = $seq->map(function ($x) {
				return $x + 1;
			});
			$expect = \range(1, 4);
			self::assertCount(\count($expect), $seq);
			$actual = $output->toArray();
			self::assertEquals($expect, $actual);
		}

		function test_filter() {
			$seq = new ArraySequence(\range(1, 3));
			$output = $seq->filter(function ($x) {
				return $x % 2 == 1;
			});
			$expect = [0 => 1, 2 => 3];
			$actual = $output->toArray();
			self::assertEQuals($expect, $actual);
		}

		function test_keys() {
			$seq = new ArraySequence([1 => 0, 3 => 1]);
			$output = $seq->keys();
			$expect = [1, 3];
			$actual = $output->toArray();
			self::assertEQuals($expect, $actual);
		}

		function test_values() {
			$seq = new ArraySequence([1 => 0, 3 => 1]);
			$output = $seq->values();
			$expect = [0, 1];
			$actual = $output->toArray();
			self::assertEQuals($expect, $actual);
		}

		function test_reduce() {
			$seq = new ArraySequence([1 => 2, 3 => 6]);
			$actual = $seq->reduce(3, function ($result, $x) {
				return $result + $x;
			});
			$expect = 11;
			self::assertSame($expect, $actual);
		}

		function test_reduce1() {
			$seq = new ArraySequence([1 => 2, 3 => 6]);
			$actual = $seq->reduce1(function ($result, $x) {
				return $result + $x;
			});
			$expect = 8;
			self::assertSame($expect, $actual);
		}

	}

}