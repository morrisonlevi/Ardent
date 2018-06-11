<?php

namespace morrisonlevi\ardent {

	interface iterator {
		function next(): maybe;
	}


	interface has_iterator {
		function get_iterator(): iterator;
	}


	function adapt_iterator(iterator $iterator) {
		loop:
		$maybe = $iterator->next();
		if ($maybe->has_value()) {
			yield $maybe->get();
			goto loop;
		}
	}


}

?>