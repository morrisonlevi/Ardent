<?php

namespace morrisonlevi\ardent {

	class DictTest extends \PHPUnit\Framework\TestCase {
		function test_basic() {
			$dict = dict::of(new _string(), new _float());
			self::assertCount(0, $dict);
			
			$dict['one-third'] = 1./3.;
			self::assertCount(1, $dict);
			self::assertSame(1./3., $dict['one-third']);
			unset($dict['one-third']);
			self::assertCount(0, $dict);
		}

		function test_bad_type_get() {
			$this->expectException(\TypeError::class);
			$dict = dict::of(new _string(), new _float());
			$dict[13];
		}

		function test_out_of_bound_get() {
			$this->expectException(\OutOfBoundsException::class);
			$dict = dict::of(new _string(), new _float());
			$dict['non-existent key'];
		}
	}

}

?>