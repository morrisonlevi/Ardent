#Exceptions

Designing a good exceptions hierarchy is more difficult than it looks. This one
has been carefully designed after looking at how Python, Java and PHP's current
SPL created their exception hierarchy. Furthermore, exceptions are not
integrated into the PHP core and many of the exceptions used in other libraries
do not apply here; there are no arithmetic or system exceptions because of
this.

## Exceptions Hierarchy

*Note: all exceptions extend `Ardent\Exception`, which extends `\Exception`.*

 - DomainException
 - FunctionException
 - LookupException
   - IndexException
   - KeyException
 - StateException
   - EmptyException
   - FullException
 - TypeException

### RIP `InvalidArgumentException`

You'll notice that `InvalidArgumentException` is no longer included. The reason
is that most of the time you throw exceptions is because an invalid argument
was passed; the name `InvalidArgument` is practically implied. Additionally,
the name `InvalidArgumentException` is vague and unhelpful.  Why is the
argument invalid?  Choosing a more descriptive exception is better, such as
choosing `TypeException` if you were passed an array while expecting a string.

### Introducing `FunctionException`

You'll also notice that instead of `BadFunctionCallException` and
`BadMethodCallException` there is `FunctionException`. The most common case for
using `BadFunctionCallException` was that you were passed something that is not
callable.  As of PHP 5.4 there is the `callable` type-hint which catches this
issue better than throwing an exception. Additionally, that is a type problem,
not a function one.

One use-case for `FunctionException` is that a user-provided function does not
return the proper type of variable and you cannot proceed.