# Redefining PHP's Standard Library: A Focus on Object-Oriented Code

While developing and helping others develop PHP applications I noticed the trend to use PHP's arrays to do nearly every task. Arrays in PHP do practically everything so they are used for everything. However, they aren't well suited to object-oriented code because arrays aren't objects. So I made this library to try to make writing object-oriented code easier for everyone.

### How can I help?

The best way to help is to use the library and [submit issues](https://github.com/morrisonlevi/Ardent/issues) when you find them. As of right now, every line in the library has been executed in a unit test.  The quality of some of these tests is poor and there are still bugs lurking around.

##### Can you add X structure?

Maybe. Open an issue and mark it as a feature request and I'll look into it. 

### Roadmap

There is no roadmap for this project. I work on it when I find something in my daily coding that could benefit this library. A few associates of mine also use this (unstable) repository and suggest changes. I intend to release version 1.0.0 when it has been successfully used in multiple projects and can commit to a stable API. Until then, minor versions will be tagged as they are deemed helpful. Once released, this project will follow [semantic versioning](http://semver.org).

#### Why not use the existing Standard PHP Library?

The current Standard PHP Library (SPL) has many problems, some of which are documented in an [RFC regarding the SPL](https://wiki.php.net/rfc/spl-improvements).  I won't go into them here, but I felt I needed to give at least some reasoning for not using it.
