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
     * @covers Spl\HashingMediator::hash
     */
    public function testAddListener() {
        $this->intercessor->addListener('error', function() {

        });

        $events = $this->intercessor->getEvents();
        $this->assertCount(1, $events['error']);
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

}
