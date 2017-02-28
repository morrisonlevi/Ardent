<?php

namespace Ardent\Algorithm;


function filter(callable $f, $iterable) {
	foreach ($iterable as $key => $value) {
		if ($f($value)) {
			yield $key => $value;
		}
	}
}

