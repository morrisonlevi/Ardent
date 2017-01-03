<?php

namespace Ardent\Algorithm;

function map(callable $f, $iterable) {
	foreach ($iterable as $key => $value) {
		yield $key => $f($value);
	}
}

