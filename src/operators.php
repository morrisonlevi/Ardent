<?php

namespace morrisonlevi\ardent {

	interface has_eq {
		function __eq__($b): bool;
	}

	interface has_hash extends has_eq {
		function __hash__(): string;
	}

	function eq($a, $b) {
		if ($a instanceof has_eq) {
			return $a->__eq__($b);
		} else {
			return $a === $b;
		}
	}

	function hash($a): string {
		if ($a instanceof has_hash) {
			return $a->__hash__();
		} else if (\is_scalar($a)) {
			return (string) $a; // will not behave like regular array keys
		} else {
			assert(\is_object($a), new \TypeError());
			return \spl_object_hash($a);
		}
	}
}

?>