<?php

use Ardent\Optional;


class OptionalTest extends \PHPUnit_Framework_TestCase {

	public
	function testSomeCount_ReturnsOne() {
		self::assertCount(1, Optional::some(null));
	}

	public
	function testNoneCount_ReturnsZero() {
		self::assertCount(0, Optional::none());
	}

	public
	function testSomeIterator_YieldsSingleExpectedValue() {
		$expect = 13;
		$iterator = Optional::some($expect)->getIterator();
		self::assertInstanceOf(\Iterator::class, $iterator);

		$iterator->rewind();
		self::assertTrue($iterator->valid());
		self::assertEquals(0, $iterator->key());
		self::assertEquals($expect, $iterator->current());

		$iterator->next();
		self::assertFalse($iterator->valid());
	}

	public
	function testNoneIterator_YieldsNoValues() {
		$iterator = Optional::none()->getIterator();
		self::assertInstanceOf(\Iterator::class, $iterator);

		$iterator->rewind();
		self::assertFalse($iterator->valid());
	}

	public
	function testFromMaybeNull_WithNull_HasCountOfZero() {
		self::assertCount(0, Optional::fromMaybeNull(null));
	}

	public
	function testFromMaybeNull_WithNonNull_HasCountOfOne() {
		self::assertCount(1, Optional::fromMaybeNull("NonNull"));
	}

	public
	function testFromMaybeFalse_WithNonFalse_HasCountOfOne() {
		self::assertCount(1, Optional::fromMaybeFalse(0));
	}

	public
	function testFromMaybeFalse_WithFalse_HasCountOfZero() {
		self::assertCount(0, Optional::fromMaybeFalse(false));
	}

	public
	function testSomeMatch_CallsOnlyIfSomeAndReturnsValue() {
		$expect = 17;
		$ifSome = function ($value) use($expect) {
			self::assertEquals($expect, $value);
			return 11;
		};

		$ifNone = function () {
			$this->fail();
		};

		$value = Optional::some($expect)->match($ifSome, $ifNone);
		self::assertEquals(11, $value);
	}

	public
	function testNoneMatch_CallsOnlyIfNoneAndReturnsValue() {
		$expect = 17;
		$ifSome = function ($unused_value) {
			$this->fail();
		};

		$ifNone = function () use($expect) {
			return $expect;
		};

		$actual = Optional::none()->match($ifSome, $ifNone);
		self::assertEquals($expect, $actual);
	}

	public
	function testSomeMap() {
		$input = 12;
		$expect = 13;
		$m = function($input) {
			return $input + 1;
		};

		$ifSome = function($input) {
			return $input;
		};

		$ifNone = function() {
			$this->fail();
		};

		$actual = Optional::some($input)
			->map($m)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testNoneMap() {
		$expect = 13;
		$m = function($input) {
			$this->fail();
		};

		$ifSome = function($input) {
			$this->fail();
		};

		$ifNone = function() use($expect) {
			return $expect;
		};

		$actual = Optional::none()
			->map($m)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testSomeFilter_WithUnmatched_ReturnsNone() {

		$predicate = function($input) {
			return false;
		};

		$ifSome = function($input) {
			$this->fail();
		};

		$expect = 13;
		$ifNone = function() use($expect) {
			return $expect;
		};

		$actual = Optional::some($expect + 1)
			->filter($predicate)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testSomeFilter_WithMatched_ReturnsSome() {
		$expect = 13;

		$predicate = function($input) {
			return true;
		};

		$ifSome = function($input) use($expect) {
			return $input;
		};

		$ifNone = function() {
			$this->fail();
		};

		$actual = Optional::some($expect)
			->filter($predicate)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testNoneFilter() {
		$expect = 13;
		$f = function($input) {
			$this->fail();
		};

		$ifSome = function($input) {
			$this->fail();
		};

		$ifNone = function() use($expect) {
			return $expect;
		};

		$actual = Optional::none()
			->filter($f)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testFromPredicate_WithMatched_ReturnsSome() {
		$expect = 13;
		$f = function($input) {
			return true;
		};

		$ifSome = function($input) {
			return $input;
		};

		$ifNone = function() {
			$this->fail();
		};

		$actual = Optional::fromPredicate($f, $expect)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testFromPredicate_WithUnmatched_ReturnsNone() {
		$expect = 13;
		$f = function($input) {
			return false;
		};

		$ifSome = function($input) {
			$this->fail();
		};

		$ifNone = function() use($expect) {
			return $expect;
		};

		$actual = Optional::fromPredicate($f, $expect)
			->match($ifSome, $ifNone);

		self::assertEquals($expect, $actual);
	}

	public
	function testUnwrap_WithValue_ReturnsValue() {
		$expect = 17;
		$actual = Optional::some($expect)->unwrap();
		self::assertEquals($expect, $actual);
	}

	public
	function testUnwrap_WithoutValue_Throws() {
		$this->expectException(RuntimeException::class);

		Optional::none()->unwrap();
	}

}
