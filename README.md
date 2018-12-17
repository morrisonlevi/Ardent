# A Library of Collections

[![Build Status](https://travis-ci.org/morrisonlevi/Ardent.svg?branch=experiment-2019)](https://travis-ci.org/morrisonlevi/Ardent)

This library aims to add algorithms and data structures that are lacking in standard PHP, or the standard version is deficient enough that it needs an alternate.

This example shows a `Vector` and a `Slice` being used, and shows that modifying the slice did not affect the original:

```php
$input = ardent\Vector::from([1, 1, 2, 3, 3]);
$input2 = new ardent\Slice($input, 0, $input->count());
ardent\unique('ardent\eq', $input2);
var_export(\iterator_to_array($input)); echo "\n";
var_export(\iterator_to_array($input2)); echo "\n";
```

### Requirements

  - Patience. This project is unstable and subject to significant changes from release to release.
  - PHP 7.1.

### Roadmap

There is no official roadmap for this project. I intend to release version 1.0.0 when I am confident enough in the API that it is suitable for public use. Until then, minor versions will be tagged as they are deemed helpful.

### How can I help?

The best way to help is to use the library (at your own risk) and [submit issues](https://github.com/morrisonlevi/Ardent/issues) when you find them. Over 99% of the library has been executed in a unit test but the quality of some of these tests is poor; improved tests are definitely welcome.

##### Can you add X structure?

Maybe. Open an issue and I'll look into it. 

### What's up with the name?

This project was originally aimed to fix the SPL; as such I had named it SPL. Eventually I realized that I would do better with a different name; I picked Ardent it describes how I feel about the need for this kind of library. Unfortunately another PHP project decided to use the name after I had already picked it.
