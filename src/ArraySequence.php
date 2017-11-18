<?php

namespace Ardent {

	final class ArraySequence implements \IteratorAggregate, \Countable {

		use Sequence;

		private $data;

		function __construct(array $data) {
			$this->data = $data;
		}

		function getIterator(): \ArrayIterator {
			return new \ArrayIterator($this->data);
		}

		function map(callable $fn): ArraySequence {
			$output = new ArraySequence([]);
			foreach ($this->data as $key => $value) {
				$output->data[$key] = $fn($value);
			}
			return $output;
		}

		function filter(callable $fn): ArraySequence {
			$output = new ArraySequence([]);
			foreach ($this->data as $key => $value) {
				if ($fn($value)) {
					$output->data[$key] = $value;
				}
			}
			return $output;
		}

		function keys(): ArraySequence {
			$output = new ArraySequence([]);
			foreach ($this->data as $key => $value) {
				$output->data[] = $key;
			}
			return $output;
		}

		function values(): ArraySequence {
			$output = new ArraySequence([]);
			foreach ($this->data as $value) {
				$output->data[] = $value;
			}
			return $output;
		}

		function count(): int {
			return \count($this->data);
		}

		function toArray(): array {
			return $this->data;
		}

	}

}

?>