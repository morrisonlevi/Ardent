<?php

namespace ardent {

	/* This uses to_assoc_array instead of to_array to ensure that holes were
	 * not formed. */
	final class VectorTest extends \PHPUnit\Framework\TestCase {

		function testEmptyProperties() {
			$vector = new Vector();
			self::assertCount(0, $vector);
		}

		function testBasicAppendAndRetrievalFunctionality() {
			$expect = [1, 3, 5];

			$vector = new Vector();
			$vector->append($expect);
			self::assertCount(\count($expect), $vector);
			foreach ($expect as $i => $value) {
				self::assertEquals($value, $vector[$i]);
			}

			$actual = to_assoc_array($vector);
			self::assertEquals($expect, $actual);
		}

		function testModifications() {

			$vector = new Vector();
			$vector[] = 7;
			$vector->append([1, 2, 3]);
			$vector[2] = 3;
			$vector[3] = 5;
			self::assertCount(4, $vector);
			foreach ([7, 1, 3, 5] as $i => $value) {
				self::assertEquals($value, $vector[$i]);
			}

			$vector->truncate(2);
			$actual = to_assoc_array($vector);
			self::assertEquals([7,1], $actual);
		}

		function testFrom() {
			$expect = [6, 3, 1];
			$vector = Vector::from($expect);
			$actual = to_assoc_array($vector);
			self::assertEquals($expect, $actual);
		}

		function testOffsetUnset() {
			$vector = Vector::from([0, 1, 2, 3, 4, 5]);
			$vector->offsetUnset(1);
			$vector->offsetUnset(3);
			$expect = [0, 2, 3, 5];
			$actual = to_assoc_array($vector);
			self::assertEquals($expect, $actual);
		}
	}

}

?>
