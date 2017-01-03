<?php

namespace Ardent\Algorithm;


function skip(int $n, $input): \Iterator {
	$iter = to_iterator($input);
	$i = 0;

	$iter->rewind();
	while ($iter->valid() && $i++ < $n) {
		$iter->next();
	}

	while ($iter->valid()) {
		yield $iter->key() => $iter->current();
		$iter->next();
	}

}

