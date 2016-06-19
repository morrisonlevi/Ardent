<?php

namespace Ardent\Collection;

use PHPUnit_Framework_TestCase;

class PriorityQueueTest extends PHPUnit_Framework_TestCase
{
    /** @var PriorityQueue */
    private $heap;

    public function setUp()
    {
        $this->heap = new PriorityQueue;

        $this->heap->enqueue(5, 5);
        $this->heap->enqueue(1, 1);
        $this->heap->enqueue(4, 4);
        $this->heap->enqueue(2, 2);
        $this->heap->enqueue(3, 3);
    }

    public function testChangePriority()
    {
        $this->heap->changePriority(5, 0);
        $this->heap->changePriority(2, 10);

        $this->assertEquals(5, $this->heap->dequeue());
        $this->assertEquals(1, $this->heap->dequeue());
        $this->assertEquals(3, $this->heap->dequeue());
        $this->assertEquals(4, $this->heap->dequeue());
        $this->assertEquals(2, $this->heap->dequeue());

        $this->assertTrue($this->heap->isEmpty());
    }

    public function testContains()
    {
        $this->assertTrue($this->heap->contains(3));
        $this->assertFalse($this->heap->contains(40));
    }

    public function testCount()
    {
        $this->assertEquals(5, $this->heap->count());

        for ($i = 5; $i--; ) {
            $this->heap->dequeue();
            $this->assertEquals($i, $this->heap->count());
        }
    }

    public function testDelete()
    {
        $this->heap->remove(3);

        $this->assertEquals(1, $this->heap->dequeue());
        $this->assertEquals(2, $this->heap->dequeue());
        $this->assertEquals(4, $this->heap->dequeue());
        $this->assertEquals(5, $this->heap->dequeue());

        $this->assertTrue($this->heap->isEmpty());
    }

    public function testHeapInsertDelete()
    {
        $this->assertEquals(1, $this->heap->dequeue());
        $this->assertEquals(2, $this->heap->dequeue());
        $this->assertEquals(3, $this->heap->dequeue());
        $this->assertEquals(4, $this->heap->dequeue());
        $this->assertEquals(5, $this->heap->dequeue());

        $this->assertTrue($this->heap->isEmpty());
    }

    public function testIteration()
    {
        $i = 1;

        foreach ($this->heap as $value) {
            $this->assertEquals($i++, $value);
        }
    }
}