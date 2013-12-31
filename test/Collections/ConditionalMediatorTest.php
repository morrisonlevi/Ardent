<?php

namespace Collections;

class ConditionalMediatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     */
    function getListenersWithNonExistentEventReturnsEmptyArray() {
        $mediator = new ConditionalMediator();
        $listeners = $mediator->getListeners('non-existent');
        $this->assertTrue(is_array($listeners));
        $this->assertEmpty($listeners);
    }

    function testAddListenerAndGetEventListeners() {
        $mediator = new ConditionalMediator();
        $mediator->addListener('do', 'strtolower');
        $mediator->addListener('do', 'base64_encode');

        $expectedListeners = ['strtolower', 'base64_encode'];
        $actualListeners = $mediator->getListeners('do');
        $this->assertEquals($expectedListeners, $actualListeners);
    }

    /**
     * @test
     * @depends testAddListenerAndGetEventListeners
     */
    function removingNonExistentListenerDoesNotAffectOtherListeners() {
        $mediator = new ConditionalMediator();

        $mediator->addListener('do', 'base64_encode');
        $mediator->removeListener('do', 'str_rot13');
        $mediator->removeListener('do not', 'str_rot13');

        $expectedListeners = ['base64_encode'];
        $actualListeners = $mediator->getListeners('do');
        $this->assertEquals($expectedListeners, $actualListeners);

    }

    /**
     * @depends testAddListenerAndGetEventListeners
     */
    function testRemoveListener() {
        $mediator = new ConditionalMediator();
        $mediator->addListener('do', 'str_rot13');
        $mediator->removeListener('do', 'str_rot13');

        $expect = [];
        $actual = $mediator->getListeners('do');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @depends testAddListenerAndGetEventListeners
     */
    function testRemoveEvent() {
        $mediator = new ConditionalMediator();
        $mediator->addListener('do', 'str_rot13');

        $mediator->removeEvent('do');

        $expect = [];
        $actual = $mediator->getListeners('do');

        $this->assertEquals($expect, $actual);
    }

    /**
     * @depends testAddListenerAndGetEventListeners
     */
    function testClear() {
        $mediator = new ConditionalMediator();
        $mediator->addListener('do', 'str_rot13');

        $mediator->clear();

        $expected = [];
        $actual = $mediator->getEvents();
        $this->assertEquals($expected, $actual);
    }

    function testNotify() {
        $mediator = new ConditionalMediator();

        $calledFunctionsCount = 0;

        $returnTrue = function(\StdClass $a) use (&$calledFunctionsCount) {
            $calledFunctionsCount++;
            return true;
        };
        $returnFalse = function(\StdClass $a) use (&$calledFunctionsCount) {
            $calledFunctionsCount++;
            return false;
        };

        $mediator->addListener('do', $returnTrue);
        $mediator->addListener('do', $returnTrue);
        $mediator->addListener('do', $returnTrue);
        $mediator->addListener('do', $returnFalse);
        $mediator->addListener('do', $returnTrue);
        $mediator->addListener('do', $returnTrue);

        $mediator->notify('do', new \StdClass());

        $this->assertEquals(4, $calledFunctionsCount);
    }

    function testNotifyEmpty() {
        $mediator = new ConditionalMediator();

        $calledFunctionsCount = 0;
        $returnTrue = function(\StdClass $a) use (&$calledFunctionsCount) {
            $calledFunctionsCount++;
            return true;
        };

        $mediator->addListener('don\'t', $returnTrue);
        $mediator->notify('do');

        $this->assertEquals(0, $calledFunctionsCount);
    }

}
