<?php

namespace {
	require __DIR__ . '/../src/ardent.php';
}

namespace morrisonlevi\ardent {

	// create a vec<int>
	$vec = vec::of(int_t());

	// append single values with operator[]=
	$vec[] = 1;

	// append multiple values with append
	// works with any iterable
	$vec->append([2, 3, 4]);

	// this would fail an assertion; by default you will not get an
	// exception unless you change your ini settings (recommended)
	// $vec[] = "not an integer";

	// vec is countable
	assert(\count($vec) == 4);

	$expect = [1, 2, 3, 4];
	$actual = [];
	// and iterable
	foreach ($vec as $value) {
		$actual[] = $value;
	}
	assert($expect === $actual);

	// unset doesn't work unless the vec's type is nullable
	// unset($vec[0]);
	// if assertion exceptions are enabled this throws LogicException
	// I am unsure if that's better or worse than TypeError
}

?>