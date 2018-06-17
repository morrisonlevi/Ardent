<?php

namespace {
	require __DIR__ . '/../src/ardent.php';
}

namespace morrisonlevi\ardent {

	// create a dict<int, float>
	$dict = dict::of(int_t(), float_t());

	// put items in with operator[]=
	$dict[0] = 0.;
	$dict[1] = 1.;
	$dict[2] = 2.;


	// These would trigger assertion errors:
	// $dict["not an int"] = NAN;
	// $dict[3] = "not a float";
	// To turn the assertion into an exception (recommended) enable it in
	// the ini setting assert.exception = 1

	// Due to a bug in PHP this doesn't actually fail, though it should.
	// https://bugs.php.net/bug.php?id=63217
	$dict["3"] = 3.;

	// dict is countable
	assert(\count($dict) == 4);

	$expect = [0 => 0., 1 => 1., 2 => 2., 3 => 3.];
	$actual = [];
	// dict is iterable; order is not guaranteed
	foreach ($dict as $key => $value) {
		$actual[$key] = $value;
	}

	// put in order to verify we visited everything
	ksort($actual);
	assert($expect === $actual);


	// Custom types can implement has_hash to customize behavior:
	class user implements has_hash {
		private $id;
		private $name;

		function __construct($id, $name) {
			$this->id = $id;
			$this->name = $name;
		}

		function __eq__($item): bool {
			assert($item instanceof user, new \TypeError());
			return $this->id == $item->id;
		}

		function __hash__(): string {
			return hash($this->id);
		}
	}

	$aliases_by_user = dict::of(class_t(user::class), array_t(string_t()));
	$aliases_by_user[new user(1, "levi")] = ['levim', 'morrisonlevi'];
	var_dump($aliases_by_user[new user(1, "Levi M")]);

	// alternatively provide hash and eq functions to dict's constructor:
	$aliases_by_user = dict::of(
		class_t(user::class),
		array_t(string_t()),
		function (user $u) {
			return $u->__hash__();
		},
		function (user $a, user $b) {
			return $a->__eq__($b);
		}
	);

	$aliases_by_user[new user(1, "levi")] = ['levim', 'morrisonlevi'];
	var_dump($aliases_by_user[new user(1, "LeviM")]);


}

?>