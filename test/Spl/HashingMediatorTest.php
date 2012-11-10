<?php
namespace Spl;

class HashingMediatorHelper extends HashingMediator {
    public function &getEvents() {
        return $this->events;
    }

    public function hash($callable) {
        return parent::hash($callable);
    }
}

class CallableStub {
    public function __invoke(){}

    public static function staticMethod(){}
}

function doNothing() {

}

class HashingMediatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var HashingMediatorHelper
     */
    protected $intercessor;

    protected function setUp() {
        $this->intercessor = new HashingMediatorHelper();
    }

    /**
     * @covers Spl\HashingMediator::addListener
     */
    public function testAddListener() {
        $this->intercessor->addListener('error', function() {

        });

        $events = $this->intercessor->getEvents();
        $this->assertCount(1, $events['error']);
    }

    /**
     * @covers \Spl\HashingMediator::addListener
     * @expectedException \Spl\TypeException
     */
    public function testAddListenerException() {
        $this->intercessor->addListener('error', 'notCallable');
    }

    /**
     * @covers Spl\HashingMediator::removeListener
     */
    public function testRemoveListener() {
        $fn = function(){};
        $events =& $this->intercessor->getEvents();
        $events['error'][$this->intercessor->hash($fn)] = $fn;

        $this->intercessor->removeListener('error', $fn);

        $this->assertCount(0, $events['error']);
    }

    /**
     * @covers Spl\HashingMediator::removeListener
     */
    public function testRemoveListenerInvalidCallable() {
        $this->intercessor->removeListener('error', 'notCallable');
    }

    /**
     * @covers Spl\HashingMediator::removeListenersForEvent
     */
    public function testRemoveListenersForEvent() {
        $fnA = function(){};
        $fnB = function(){};
        $events =& $this->intercessor->getEvents();
        $events['error'][$this->intercessor->hash($fnA)] = $fnA;
        $events['error'][$this->intercessor->hash($fnB)] = $fnB;

        $this->intercessor->removeListenersForEvent('error');

        $this->assertCount(0, $events['error']);
    }

    /**
     * @covers Spl\HashingMediator::removeAllListeners
     */
    public function testRemoveAllListeners() {
        $fn = function(){};
        $events =& $this->intercessor->getEvents();
        $events['error'][$this->intercessor->hash($fn)] = $fn;
        $events['test'][$this->intercessor->hash($fn)] = $fn;

        $this->intercessor->removeAllListeners();

        $this->assertCount(0, $events);
    }

    /**
     * @covers Spl\HashingMediator::notify
     */
    public function testNotify() {
        $fnA = $this->getMock(
            'Spl\\CallableStub',
            array('__invoke')
        );
        $fnB = $this->getMock(
            'Spl\\CallableStub',
            array('__invoke')
        );
        $events =& $this->intercessor->getEvents();
        $events['test'][$this->intercessor->hash($fnA)] = $fnA;
        $events['test'][$this->intercessor->hash($fnB)] = $fnB;

        $paramA = 1;
        $paramB = new \StdClass();

        $fnA->expects($this->once())
            ->method('__invoke')
            ->with($this->equalTo($paramA), $this->equalTo($paramB));

        $fnB->expects($this->once())
            ->method('__invoke')
            ->with($this->equalTo($paramA), $this->equalTo($paramB));

        $this->intercessor->notify('test', $paramA, $paramB);
    }

    /**
     * @covers Spl\HashingMediator::hash
     */
    public function testHash() {
        $invokableObject = new CallableStub();
        $callables = array(
            'invokableObject' => $invokableObject,
            'arrayCallableA' => array($invokableObject, '__invoke'),
            'arrayCallableB' => array($invokableObject, '__invoke'),
            'anonymousFunction' => function(){},
            'globalFunction' => 'Spl\\doNothing',
            'staticArrayCallable' => array('Spl\\CallableStub', 'staticMethod'),
            'staticCallable' => 'Spl\\CallableStub::staticMethod',
        );

        $expectedHashes = array(
            'invokableObject' => spl_object_hash($invokableObject),
            'arrayCallableA' => spl_object_hash($invokableObject) . '__invoke',
            'arrayCallableB' => spl_object_hash($invokableObject) . '__invoke',
            'anonymousFunction' => spl_object_hash($callables['anonymousFunction']),
            'globalFunction' => 'Spl\\doNothing',
            'staticArrayCallable' => 'Spl\\CallableStub::staticMethod',
            'staticCallable' => 'Spl\\CallableStub::staticMethod',
        );

        foreach ($callables as $name => $callable) {
            $actualHashes[$name] = $this->intercessor->hash($callable);
        }

        $this->assertEquals($expectedHashes, $actualHashes);
        $this->assertEquals($expectedHashes['arrayCallableA'], $expectedHashes['arrayCallableB']);
    }

}
