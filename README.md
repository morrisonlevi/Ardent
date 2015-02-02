# A Library of Collections for OO Programming

While developing and helping others develop PHP applications I noticed the trend to use PHP's arrays in nearly every task. Arrays in PHP are useful but are overused because PHP doesn't have rich standard libraries for working with common data structures and algorithms. This library hopes to fill in that gap. Undoubtably, I've made mistakes in design and implementation; hopefully more community involvement can help identify and fix such mistakes.

## Requirements

##### Patience
This project is unstable and subject to significant changes from release to release. Stability is not a goal during this phase of development. To be clear: you are using this project at your own risk. I highly value your experience using this library and am thankful for the early adopters, but I want to emphasize that this project is highly unstable.

##### PHP 5.4
This project requires **PHP 5.4** because PHP 5.3 is at its End Of Life (EOL). As such the project makes use of Traits, the callable type and short array syntax. Depending on how long this project takes to mature it might require PHP 5.5 for generators and improved handling of non-scalar keys in `foreach` loops, or maybe PHP 5.6 for `…` support.

You can also use HHVM. I won't let HHVM compatibility stop me from doing what I feel is right for this library, but hopefully we can maintain compatibility.

## Roadmap

There is no official roadmap for this project. I intend to release version 1.0.0 when I am confident enough in the API that it is suitable for public use. Until then, minor versions will be tagged as they are deemed helpful. This project follows [semantic versioning](http://semver.org).

## How can I help?

The best way to help is to use the library (again, at your own risk) and [submit issues](https://github.com/morrisonlevi/Ardent/issues) when you find them. Over 99% of the library has been executed in a unit test but the quality of some of these tests is poor; improved tests are definitely welcome.

##### Can you add X structure?

Maybe. Open an issue and I'll look into it. 

## Why not use the existing Standard PHP Library?

The Standard PHP Library (SPL) has many problems, some of which are documented in an [unfinished RFC regarding the SPL](https://wiki.php.net/rfc/spl-improvements). I won't go into full details here, but essentially the SPL is not providing the standardized data structures and algorithms that I feel the PHP community needs.

### What's up with the name?

This project was originally aimed to fix the SPL; as such I had named it SPL. Eventually I realized that I would do better with a different name; I picked Ardent it describes how I feel about the need for this kind of library. Right now the Ardent namespace isn't used within the project at all. I will eventually move away from the name Ardent because another PHP project decided to use the name after I had already picked it. I am not sure what the final name will be, so for now they are simply "Collections".
