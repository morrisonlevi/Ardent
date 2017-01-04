<?php

namespace Ardent\Algorithm;


function choose(callable $f, $iterable) {
	foreach ($iterable as $k => $v) {
		$opt = $f($v);
		assert($opt instanceof \Ardent\Optional);
		foreach ($opt as $w) {
			yield $k => $w;
		}
	}
}

