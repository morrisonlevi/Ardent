<?php

namespace Ardent\Algorithm;

use Ardent\Optional;


function reduce(callable $f, $input): Optional {
	$iter = to_iterator($input);

	$iter->rewind();

	if ($iter->valid()) {
		$carry = $iter->current();
		for ($iter->next(); $iter->valid(); $iter->next()) {
			$carry = $f($carry, $iter->current());
		}
		return Optional::some($carry);

	} else {
		return Optional::none();
	}

}

