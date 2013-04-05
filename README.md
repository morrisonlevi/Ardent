# Redefining PHP's Standard Library: A Focus on Object-Oriented Programming

While developing and helping others develop PHP applications I noticed the trend to use PHP's arrays in nearly every task. Arrays in PHP are useful but they aren't well suited to object-oriented programming since they are not objects. I made this library to try to make writing object-oriented code easier for everyone.

## Requirements

##### PHP 5.4
This project requires **PHP 5.4** because it uses Traits, the callable type-hint and short-array syntax. Depending on how long this project takes to mature it might require PHP 5.5 for its generators and improved handling of non-scalar keys in Iterators.

##### An Autoloader
This project does not provide an autoloader. This project uses a natural 1-to-1 mapping of the class or interface name to the filesystem. This project supports the tool [composer](http://getcomposer.org/) which can generate an autoloader for you.

##### Patience
This project is unstable and subject to significant changes from release to release. Therefore it requires that you exercise patience if you use it in your project.

## Roadmap

There is no roadmap for this project. I work on it when I find something in my daily coding that could benefit this library. A few associates of mine also use this (unstable) repository and suggest changes. I intend to release version 1.0.0 when it has been successfully used in multiple projects and can commit to a stable API. Until then, minor versions will be tagged as they are deemed helpful. This project follows [semantic versioning](http://semver.org).

## How can I help?

The best way to help is to use the library and [submit issues](https://github.com/morrisonlevi/Ardent/issues) when you find them. As of right now, every line in the library has been executed in a unit test\*.  The quality of some of these tests is poor and there are still bugs lurking around.

\*  *Except the Trie class which is undergoing the designing phase. Please do not use the Trie structure.*

##### Can you add X structure?

Maybe. Open an issue and mark it as a feature request and I'll look into it. 

## Why not use the existing Standard PHP Library?

The current Standard PHP Library (SPL) has many problems, some of which are documented in an [RFC regarding the SPL](https://wiki.php.net/rfc/spl-improvements).  I won't go into them here, but I felt I needed to give at least some reasoning for not using it.
