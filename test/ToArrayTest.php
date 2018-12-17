<?php

namespace ardent {

	final class ToArrayTest extends \PHPUnit\Framework\TestCase {
		function testEmptyArray() {
			$input = [];
			$expect = [];
			$actual = to_array($input);
			self::assertEquals($expect, $actual);
		}

		function testEmptyIterator() {
			$input = new \EmptyIterator();
			$expect = [];
			$actual = to_array($input);
			self::assertEquals($expect, $actual);
		}

		function testAssocArray() {
			$input = [
				1 => 0,
				3 => 2,
				5 => 4,
			];
			$expect = [0, 2, 4];
			$actual = to_array($input);
			self::assertEquals($expect, $actual);
		}

		function testAssocIterator() {
			$input = (function() {
				yield 1 => 0;
				yield 3 => 2;
				yield 5 => 4;
			})();
			$expect = [0, 2, 4];
			$actual = to_array($input);
			self::assertEquals($expect, $actual);
		}
	}
}

?>
