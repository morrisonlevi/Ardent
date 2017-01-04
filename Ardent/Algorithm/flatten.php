<?php

namespace Ardent\Algorithm;


function flatten($iterable): \Iterator {
	foreach ($iterable as $xs) {
		foreach ($xs as $x) {
			yield $x;
		}
	}
}

