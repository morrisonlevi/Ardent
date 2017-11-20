<?php

namespace Ardent {

	use PHPUnit\Framework\TestCase;

	class KeysSequenceTest extends TestCase {

		static function createFromArray(array $data) {
			return new KeysSequence(new \ArrayIterator($data));
		}

		function test_keys() {
			$seq = self::createFromArray([1 => 0, 3 => 1]);
			$output = $seq->keys();
			$expect = [0, 1];
			$actual = \iterator_to_array($output);
			self::assertEquals($expect, $actual);
		}

		function test_values() {
			$seq = self::createFromArray([1 => 0, 3 => 1]);
			$output = $seq->values();
			$expect = [1, 3];
			$actual = \iterator_to_array($output);
			self::assertEquals($expect, $actual);
		}

	}

}