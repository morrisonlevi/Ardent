<?php

use Ardent\Option;


class OptionTest extends \PHPUnit_Framework_TestCase {

	public
	function testSomeCount_ReturnsOne() {
		$this->assertCount(1, Option::some(null));
	}

	public
	function testNoneCount_ReturnsZero() {
		$this->assertCount(0, Option::none());
	}

	public
	function testSomeIterator_YieldsSingleExpectedValue() {
		$expect = 13;
		$iterator = Option::some($expect)->getIterator();
		$this->assertInstanceOf(\Iterator::class, $iterator);

		$iterator->rewind();
		$this->assertTrue($iterator->valid());
		$this->assertEquals(0, $iterator->key());
		$this->assertEquals($expect, $iterator->current());

		$iterator->next();
		$this->assertFalse($iterator->valid());
	}

	public
	function testNoneIterator_YieldsNoValues() {
		$iterator = Option::none()->getIterator();
		$this->assertInstanceOf(\Iterator::class, $iterator);

		$iterator->rewind();
		$this->assertFalse($iterator->valid());
	}

	public
	function testFromMaybeNull_WithNull_HasCountOfZero() {
		$this->assertCount(0, Option::fromMaybeNull(null));
	}

	public
	function testFromMaybeNull_WithNonNull_HasCountOfOne() {
		$this->assertCount(1, Option::fromMaybeNull("NonNull"));
	}

	public
	function testFromMaybeFalse_WithNonFalse_HasCountOfOne() {
		$this->assertCount(1, Option::fromMaybeFalse(0));
	}

	public
	function testFromMaybeFalse_WithFalse_HasCountOfZero() {
		$this->assertCount(0, Option::fromMaybeFalse(false));
	}

	public
	function testSomeMatch_CallsOnlyIfSomeAndReturnsValue() {
		$expect = 17;
		$ifSome = function ($value) use($expect) {
			$this->assertEquals($expect, $value);
			return 11;
		};

		$ifNone = function () {
			$this->fail();
		};

		$value = Option::some($expect)->match($ifSome, $ifNone);
		$this->assertEquals(11, $value);
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

		$actual = Option::none()->match($ifSome, $ifNone);
		$this->assertEquals($expect, $actual);
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

		$actual = Option::some($input)
			->map($m)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
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

		$actual = Option::none()
			->map($m)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
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

		$actual = Option::some($expect + 1)
			->filter($predicate)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
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

		$actual = Option::some($expect)
			->filter($predicate)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
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

		$actual = Option::none()
			->filter($f)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
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

		$actual = Option::fromPredicate($f, $expect)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
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

		$actual = Option::fromPredicate($f, $expect)
			->match($ifSome, $ifNone);

		$this->assertEquals($expect, $actual);
	}

}
