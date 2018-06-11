<?php

namespace morrisonlevi\ardent {

	class VecTest extends \PHPUnit\Framework\TestCase {
		function test_basic() {
			$vec = vec::of(int_t());
			self::assertCount(0, $vec);

			$vec->append(1, 3, 7);
			self::assertCount(3, $vec);
			self::assertSame(1, $vec[0]);
			self::assertSame(3, $vec[1]);
			self::assertSame(7, $vec[2]);

			$vec[2] = 5;
			self::assertSame(5, $vec[2]);
			self::assertTrue(isset($vec[1]));
		}

		function test_unset_supported() {
			$vec = vec::of(nullable_t::of(int_t()));
			$vec[] = 1;
			unset($vec[0]);
			self::assertSame(null, $vec[0]);
			self::assertCount(1, $vec);
		}

		function test_iterator() {
			$vec = vec::of(int_t());

			$expect = [1, 3, 7];
			$vec->append(... $expect);
			$actual = \iterator_to_array($vec);
			self::assertSame($expect, $actual);
		}

		function test_type_parameters() {
			$int = int_t();
			$vec = vec::of($int);
			$type_parameters = $vec->type_parameters();
			$type_arguments = $vec->type_arguments();

			self::assertSameSize($type_parameters, $type_arguments);
			self::assertSame([0 => 'T'], $type_parameters);
			self::assertSame([0 => $int], $type_arguments);
		}

		function test_bad_type_append() {
			$this->expectException(\TypeError::class);
			$vec = vec::of(int_t());
			$vec->append("1");
		}

		function test_bad_type_get() {
			$this->expectException(\TypeError::class);
			$vec = vec::of(int_t());
			$vec[] = 1;
			$vec["a"];
		}

		function test_out_of_bound_get() {
			$this->expectException(\OutOfBoundsException::class);
			$vec = vec::of(int_t());
			$vec[0];
		}

		function test_out_of_bound_set() {
			$this->expectException(\OutOfBoundsException::class);
			$vec = vec::of(bool_t());
			$vec[0] = true;
		}
	}

}

?>