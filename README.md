# Ardent: collections and sequences for PHP

[![Build Status](https://travis-ci.org/morrisonlevi/Ardent.svg?branch=master)](https://travis-ci.org/morrisonlevi/Ardent)

While developing and helping others develop PHP applications I noticed the trend to use PHP's arrays in nearly every task. Arrays in PHP are useful but are overused because PHP doesn't have rich standard libraries for working with common data structures and algorithms. This library hopes to fill in that gap. Undoubtably, I've made mistakes in design and implementation; hopefully more community involvement can help identify and fix such mistakes.

## Requirements

##### Patience
This project is unstable and subject to significant changes from release to release. Stability is not a goal during this phase of development. To be clear: you are using this project at your own risk. I highly value your experience using this library and am thankful for the early adopters, but I want to emphasize that this project is highly unstable.

##### PHP 7.1
This project requires **PHP 7.1** because I am a contributor to the PHP language itself and get annoyed when I can't use recent features. PHP 7.1 is the latest stable as of the time of this writing.

## Roadmap

There is no official roadmap for this project. I intend to release version 1.0.0 when I am confident enough in the API that it is suitable for public use. Until then, minor versions will be tagged as they are deemed helpful. This project will follow [semantic versioning](http://semver.org) when it hits 1.0.0.

## Design

The design of this library uses traits because they cannot be type-checked against at runtime and because they can be overriden without breaking the Liskov Substitution Principle. If PHP had a more advanced type system then a different design probably would have been selected.

A `Sequence` is the basic unit. It provides:
  - `map`
  - `filter`
  - `reduce`
  - `reduce1`
  - and more

It requires that you provide a `getIterator` method that returns an `Iterator`. Users are permitted *and encouraged* to specialize the parameter and return types of `Sequence` methods when useful. It was specifically designed to permit this.

To aid adoption this library provides adapter classes:

  - `ArraySequence`
  - `IteratorSequence`

The `ArraySequence` specializes some methods to return `ArraySequences` instead of directly using iterators. This makes them eager instead of lazy. If lazy behavior is preferred create an `IteratorSequence` and use that instead. The `ArraySequence` also has some methods that don't exist on other `Sequence`s.

## How can I help?

The best way to help is to use the library (again, at your own risk) and [submit issues](https://github.com/morrisonlevi/Ardent/issues) when you find them. Over 99% of the library has been executed in a unit test but the quality of some of these tests is poor; improved tests are definitely welcome.

##### Can you add X structure?

Maybe. Open an issue and I'll look into it. 

## Why not use the existing Standard PHP Library?

The Standard PHP Library (SPL) has many problems, some of which are documented in an [unfinished RFC regarding the SPL](https://wiki.php.net/rfc/spl-improvements). I won't go into full details here, but essentially the SPL is not providing the standardized data structures and algorithms that I feel the PHP community needs.

### What's up with the name?

This project was originally aimed to fix the SPL; as such I had named it SPL. Eventually I realized that I would do better with a different name; I picked Ardent it describes how I feel about the need for this kind of library. Unfortunately another PHP project decided to use the name after I had already picked it.

## Example

Dictionary is a simple example of using Sequence. Dictionary is *not* part of Ardent. It's not provided because people want their dictionaries to behave differently on missing keys, permitted keys, hashing the key, etc. It specializes `filter` to return a Dictionary instead of the default of `FilteringSequence`.

```php
final class Dictionary implements \IteratorAggregate, \Countable, \ArrayAccess {
	use \Ardent\Sequence;

	private $data = [];

	function offsetGet($key) {
		return $this->data[$key];
	}

	function offsetSet($key, $value) {
		$this->data[$key] = $value;
	}

	function offsetUnset($key): void {
		unset($this->data[$key]);
	}

	function offsetExists($key): bool {
		return isset($this->data[$key]);
	}

	function count(): int {
		return \count($this->data);
	}

	function getIterator(): \Iterator {
		return new \ArrayIterator($this->data);
	}

	/**
	 * @param callable($current,$key): bool $fn Return bool to keep
	 */
	function filter(callable $fn): self {
		$output = new self();
		foreach ($this->getIterator() as $key => $value) {
			if ($fn($value, $key)) {
				$output[$key] = $value;
			}
		}
		return $output;
	}

}
```
