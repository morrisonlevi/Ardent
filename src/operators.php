<?php

namespace morrisonlevi\ardent {

	interface has_eq {
		function eq($b): bool;
	}

	interface has_hash {
		function hash(): string;
	}

	function eq($a, $b) {
		if ($a instanceof has_eq) {
			return $a->eq($b);
		} else {
			return $a === $b;
		}
	}

	function hash($a): string {
		if ($a instanceof has_hash) {
			return $a->hash();
		} else if (\is_scalar($a)) {
			return (string) $a; // will not behave like regular array keys
		} else {
			assert(\is_object($a), new \TypeError());
			return \spl_object_hash($a);
		}
	}
}

?>