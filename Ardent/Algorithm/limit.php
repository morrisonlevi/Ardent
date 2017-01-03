<?php

namespace Ardent\Algorithm;


function limit(int $n, $input): \Iterator {
	$iter = to_iterator($input);
	$i = 0;
	while ($iter->valid() && $i++ < $n) {
		yield $iter->key() => $iter->current();
		$iter->next();
	}

}

