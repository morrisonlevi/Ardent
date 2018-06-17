<?php

namespace morrisonlevi\ardent {

	class DictTest extends \PHPUnit\Framework\TestCase {
		function test_basic() {
			$dict = dict::of(string_t(), float_t());
			self::assertCount(0, $dict);
			
			$dict['one-third'] = 1./3.;
			self::assertCount(1, $dict);
			self::assertSame(1./3., $dict['one-third']);
			unset($dict['one-third']);
			self::assertCount(0, $dict);
		}

		function test_bad_type_get() {
			$this->expectException(\TypeError::class);
			$dict = dict::of(string_t(), float_t());
			$dict[13];
		}

		function test_out_of_bound_get() {
			$this->expectException(\OutOfBoundsException::class);
			$dict = dict::of(string_t(), float_t());
			$dict['non-existent key'];
		}

		function test_iterator() {
			$dict = dict::of(int_t(), bool_t());

			$keys = [1, 3, 5, 7, 9];
			$values = [true, true, true, true, false];

			assert(\count($keys) == \count($values));
			$map = \array_combine($keys, $values);
			foreach ($map as $key => $value) {
				$dict[$key] = $value;
			}

			$visited = [];
			foreach ($dict as $key => $value) {
				self::assertSame($map[$key], $value);
				$visited[$key] = $value;
			}

			ksort($visited);
			self::assertSame($map, $visited);

		}
	}

}

?>