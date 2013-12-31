<?php

namespace Collections;

class CallableStub {
    function __invoke(){}

    static function staticMethod(){}
}

function doNothing() {

}

class HashingMediatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var HashingMediator
     */
    protected $intercessor;

    protected function setUp() {
        $this->intercessor = new HashingMediator();
    }

    function testAddListener() {
        $this->intercessor->addListener('do', $doNothing = function() {

        });

        $expected = [$doNothing];
        $actual = $this->intercessor->getListeners('do');
        $this->assertEquals($expected, $actual);
    }

    function testRemoveListener() {
        $fn = function(){};
        $this->intercessor->addListener('do', $fn);
        $this->intercessor->removeListener('do', $fn);

        $this->assertEmpty($this->intercessor->getListeners('do'));
    }

    function testRemoveEvent() {
        $fn = 'str_rot13';

        $this->intercessor->addListener('do', $fn);
        $this->intercessor->addListener('hast', $fn);
        $this->intercessor->removeEvent('do');

        $expected = ['hast'];
        $actual = $this->intercessor->getEvents();
        $this->assertEquals($expected, $actual);
    }

    function testClear() {
        $this->intercessor->addListener('do', 'str_rot13');

        $this->intercessor->clear();

        $expected = [];
        $actual = $this->intercessor->getEvents();
        $this->assertEquals($expected, $actual);
    }

    function testNotify() {
        $fnA = $this->getMock(
            'Collections\\CallableStub',
            array('__invoke')
        );
        $fnB = $this->getMock(
            'Collections\\CallableStub',
            array('__invoke')
        );
        $this->intercessor->addListener('do', $fnA);
        $this->intercessor->addListener('do', $fnB);

        $paramA = 1;
        $paramB = new \StdClass();

        $fnA->expects($this->once())
            ->method('__invoke')
            ->with($this->equalTo($paramA), $this->equalTo($paramB));

        $fnB->expects($this->once())
            ->method('__invoke')
            ->with($this->equalTo($paramA), $this->equalTo($paramB));

        $this->intercessor->notify('do', $paramA, $paramB);
        $this->intercessor->notify('nonExistentEvent');
    }

    function testHash() {
        $invokableObject = new CallableStub();
        $callables = array(
            'invokableObject' => $invokableObject,
            'arrayCallableA' => array($invokableObject, '__invoke'),
            'arrayCallableB' => array($invokableObject, '__invoke'),
            'anonymousFunction' => function(){},
            'globalFunction' => 'Collections\\doNothing',
            'staticArrayCallable' => array('Collections\\CallableStub', 'staticMethod'),
            'staticCallable' => 'Collections\\CallableStub::staticMethod',
        );

        $expectedHashes = array(
            'invokableObject' => spl_object_hash($invokableObject),
            'arrayCallableA' => spl_object_hash($invokableObject) . '__invoke',
            'arrayCallableB' => spl_object_hash($invokableObject) . '__invoke',
            'anonymousFunction' => spl_object_hash($callables['anonymousFunction']),
            'globalFunction' => 'Collections\\doNothing',
            'staticArrayCallable' => 'Collections\\CallableStub::staticMethod',
            'staticCallable' => 'Collections\\CallableStub::staticMethod',
        );

        $actualHashes = array();
        foreach ($callables as $name => $callable) {
            $actualHashes[$name] = $this->intercessor->hash($callable);
        }

        $this->assertEquals($expectedHashes, $actualHashes);
        $this->assertEquals($expectedHashes['arrayCallableA'], $expectedHashes['arrayCallableB']);
    }

    /**
     * @test
     */
    function getEventListenersWithNonExistentEventReturnsEmptyArray() {
        $listeners = $this->intercessor->getListeners('non-existent');
        $this->assertTrue(is_array($listeners));
        $this->assertEmpty($listeners);
    }

    function testGetEventListeners() {
        $this->intercessor->addListener('do', 'strtolower');
        $this->intercessor->addListener('do', 'base64_encode');

        $expectedListeners = ['strtolower', 'base64_encode'];
        $actualListeners = $this->intercessor->getListeners('do');
        $this->assertEquals($expectedListeners, $actualListeners);
    }

    function testGetEventsEmpty() {
        $expected = [];
        $actual = $this->intercessor->getEvents();
        $this->assertEquals($expected, $actual);
    }

    function testGetEvents() {
        $this->intercessor->addListener('do', 'str_rot13');

        $expected = ['do'];
        $actual = $this->intercessor->getEvents();
        $this->assertEquals($expected, $actual);
    }

}
