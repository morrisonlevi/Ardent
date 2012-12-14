<?php
namespace Ardent;

class HashingMediatorHelper extends HashingMediator {
    public function &getEvents() {
        return $this->events;
    }

    public function hash($callable) {
        return parent::hash($callable);
    }
}

class CallableStub {
    function __invoke(){}

    static function staticMethod(){}
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
     * @covers Ardent\HashingMediator::addListener
     */
    function testAddListener() {
        $this->intercessor->addListener('error', function() {

        });

        $events = $this->intercessor->getEvents();
        $this->assertCount(1, $events['error']);
    }

    /**
     * @covers \Ardent\HashingMediator::addListener
     * @expectedException \Ardent\TypeException
     */
    function testAddListenerException() {
        $this->intercessor->addListener('error', 'notCallable');
    }

    /**
     * @covers Ardent\HashingMediator::removeListener
     */
    function testRemoveListener() {
        $fn = function(){};
        $events =& $this->intercessor->getEvents();
        $events['error'][$this->intercessor->hash($fn)] = $fn;

        $this->intercessor->removeListener('error', $fn);

        $this->assertCount(0, $events['error']);
    }

    /**
     * @covers Ardent\HashingMediator::removeListener
     */
    function testRemoveListenerInvalidCallable() {
        $this->intercessor->removeListener('error', 'notCallable');
    }

    /**
     * @covers Ardent\HashingMediator::removeListenersForEvent
     */
    function testRemoveListenersForEvent() {
        $fnA = function(){};
        $fnB = function(){};
        $events =& $this->intercessor->getEvents();
        $events['error'][$this->intercessor->hash($fnA)] = $fnA;
        $events['error'][$this->intercessor->hash($fnB)] = $fnB;

        $this->intercessor->removeListenersForEvent('error');

        $this->assertCount(0, $events['error']);
    }

    /**
     * @covers Ardent\HashingMediator::removeAllListeners
     */
    function testRemoveAllListeners() {
        $fn = function(){};
        $events =& $this->intercessor->getEvents();
        $events['error'][$this->intercessor->hash($fn)] = $fn;
        $events['test'][$this->intercessor->hash($fn)] = $fn;

        $this->intercessor->removeAllListeners();

        $this->assertCount(0, $events);
    }

    /**
     * @covers Ardent\HashingMediator::notify
     */
    function testNotify() {
        $fnA = $this->getMock(
            'Ardent\\CallableStub',
            array('__invoke')
        );
        $fnB = $this->getMock(
            'Ardent\\CallableStub',
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

        $this->intercessor->notify('nonExistentEvent');
    }

    /**
     * @covers Ardent\HashingMediator::hash
     */
    function testHash() {
        $invokableObject = new CallableStub();
        $callables = array(
            'invokableObject' => $invokableObject,
            'arrayCallableA' => array($invokableObject, '__invoke'),
            'arrayCallableB' => array($invokableObject, '__invoke'),
            'anonymousFunction' => function(){},
            'globalFunction' => 'Ardent\\doNothing',
            'staticArrayCallable' => array('Ardent\\CallableStub', 'staticMethod'),
            'staticCallable' => 'Ardent\\CallableStub::staticMethod',
        );

        $expectedHashes = array(
            'invokableObject' => spl_object_hash($invokableObject),
            'arrayCallableA' => spl_object_hash($invokableObject) . '__invoke',
            'arrayCallableB' => spl_object_hash($invokableObject) . '__invoke',
            'anonymousFunction' => spl_object_hash($callables['anonymousFunction']),
            'globalFunction' => 'Ardent\\doNothing',
            'staticArrayCallable' => 'Ardent\\CallableStub::staticMethod',
            'staticCallable' => 'Ardent\\CallableStub::staticMethod',
        );

        $actualHashes = array();
        foreach ($callables as $name => $callable) {
            $actualHashes[$name] = $this->intercessor->hash($callable);
        }

        $this->assertEquals($expectedHashes, $actualHashes);
        $this->assertEquals($expectedHashes['arrayCallableA'], $expectedHashes['arrayCallableB']);
    }

}
