<?php

namespace Ardent\Algorithm;


function fold(callable $f, $initial, $iterable) {
	$carry = $initial;
	foreach ($iterable as $value) {
		$carry = $f($carry, $value);
	}
	return $carry;
}

