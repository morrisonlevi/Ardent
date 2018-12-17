<?php

namespace ardent {

	final class VectorTest extends \PHPUnit\Framework\TestCase {

		function testEmptyProperties() {
			$vector = new Vector();
			self::assertCount(0, $vector);
		}

		function testBasicFunctionality() {
			$expect = [1, 3, 5];

			$vector = new Vector();
			$vector->append($expect);
			self::assertCount(\count($expect), $vector);
			foreach ($expect as $i => $value) {
				self::assertEquals($value, $vector[$i]);
			}

			$actual = to_array($vector);
			self::assertEquals($expect, $actual);
		}

	}

}

?>
