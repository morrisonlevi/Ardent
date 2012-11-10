#Exceptions

Designing a good exceptions hierarchy is more difficult that it looks. This one
has been carefully designed after looking at how Python, Java and PHP's current
SPL created their exception hierarchy. Furthermore, exceptions are not
integrated into the PHP core and many of the exceptions used in other libraries
do not apply here; there are no arithmetic or system exceptions because of
this.

You'll notice that `InvalidArgumentException` is no longer included. The reason
is that most of the time you throw exceptions is because an invalid argument
was passed; the name `InvalidArgument` is practically implied. Additionally,
the name `InvalidArgumentException` is vague and unhelpful.  Why is the
argument invalid?  Choosing a more descriptive exception is better.

## Exceptions Hierarchy

*Note: all exceptions extend `Spl\Exception`, which extends `\Exception`.*

 - DomainException
 - TypeException
 - LookupException
   - IndexException
   - KeyException
 - StateException
   - EmptyException
   - FullException