# A Libray of Collections for OO Programming

While developing and helping others develop PHP applications I noticed the trend to use PHP's arrays in nearly every task. Arrays in PHP are useful but they aren't well suited to object-oriented programming since they are not objects. I made this library to make writing object-oriented code easier and more expressive.

## Requirements

##### PHP 5.4
This project requires **PHP 5.4** because it uses Traits, the callable type-hint and short-array syntax. Depending on how long this project takes to mature it might require PHP 5.5 for generators and improved handling of non-scalar keys in `foreach` loops.

You can also use HHVM. I won't let HHVM compatibility stop me from using features in PHP 5.5 or 5.6 if I feel those features will help me, but for now we are compatible.

##### Patience
This project is unstable and subject to significant changes from release to release. Therefore it requires that you exercise patience if you use it in your project.

## Roadmap

There is no official roadmap for this project. I intend to release version 1.0.0 when I am confident enough in the API that it is suitable for public use. Until then, minor versions will be tagged as they are deemed helpful. This project follows [semantic versioning](http://semver.org).

## How can I help?

The best way to help is to use the library and [submit issues](https://github.com/morrisonlevi/Ardent/issues) when you find them. Over 99% of the library has been executed in a unit test but the quality of some of these tests is poor; improved tests are definitely welcome.

##### Can you add X structure?

Maybe. Open an issue and mark it as a feature request and I'll look into it. 

## Why not use the existing Standard PHP Library?

The Standard PHP Library (SPL) has many problems, some of which are documented in an [unfinished RFC regarding the SPL](https://wiki.php.net/rfc/spl-improvements).  I won't go into them here, but I felt I needed to give at least some reasoning for not using it.

### What's up with the name?

This project was originally aimed to fix the SPL; as such I had named it SPL. Eventually I realized that I would do better with a different name; I picked Ardent it describes how I feel about the need for this kind of library. Right now the Ardent namespace isn't used within the project at all. I will eventually move away from the name Ardent because another PHP project decided to use the name after I had already picked it. I am not sure what the final name will be, so for now they are simply "Collections".
