<?php

namespace Ardent\Algorithm;


function choose(callable $f, $iterable): \Iterator {
	foreach ($iterable as $k => $v) {
		$opt = $f($v);
		assert($opt instanceof \Ardent\Optional);
		foreach ($opt as $w) {
			yield $k => $w;
		}
	}
}

