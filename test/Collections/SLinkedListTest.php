<?php

namespace Collections;


class SLinkedListTest extends TestCase {


    function instance() {
        return new SLinkedList();
    }


    function test_count_empty_returnsZero() {
        $list = $this->instance();
        $this->assertCount(0, $list);
    }


    function test_count_pushWhenEmpty_returnsOne() {
        $list = $this->instance();
        $list->push(0);
        $this->assertCount(1, $list);
    }


    function test_count_unshiftWhenEmpty_size1() {
        $list = $this->instance();
        $list->unshift(0);
        $this->assertCount(1, $list);
    }


    function test_isEmpty_empty_returnsTrue() {
        $list = $this->instance();
        $this->assertTrue($list->isEmpty());
    }


    function test_isEmpty_notEmpty_returnsFalse() {
        $list = $this->instance();
        $list->push(0);
        $this->assertFalse($list->isEmpty());
    }


    function test_pop_empty_throwsException() {
        $this->setExpectedException('\Collections\EmptyException');
        $list = $this->instance();
        $list->pop();
    }


    function test_shift_empty_throwsException() {
        $this->setExpectedException('\Collections\EmptyException');
        $list = $this->instance();
        $list->shift();
    }


    function test_first_empty_throwsException() {
        $this->setExpectedException('\Collections\EmptyException');
        $list = $this->instance();
        $list->first();
    }


    function test_last_empty_throwsException() {
        $this->setExpectedException('\Collections\EmptyException');
        $list = $this->instance();
        $list->last();
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_pop_sizeN_returnsNValue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = end($data);
        $actual = $list->pop();
        $this->assertEquals($expect, $actual);
    }


    /**
     * @depends test_pop_sizeN_returnsNValue
     * @dataProvider provide_rangeOneToN
     */
    function test_offsetSet_withNull_pushesValue(array $data) {
        $list = $this->instance();
        foreach ($data as $value) {
            $list[] = $value;
        }

        $expect = $value;
        $actual = $list->pop();
        $this->assertEquals($expect, $actual);
    }


    /**
     * @depends test_pop_sizeN_returnsNValue
     * @dataProvider provide_rangeOneToN
     */
    function test_count_offsetSetWithNull_returnsN(array $data) {
        $list = $this->instance();
        foreach ($data as $value) {
            $list[] = $value;
        }

        $expect = count($data);
        $this->assertCount($expect, $list);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_count_pushNThenPop_returnsNMinusOne(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = count($data) - 1;
        $list->pop();
        $this->assertCount($expect, $list);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_shift_sizeN_returnsFirstValue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = reset($data);
        $actual = $list->shift();
        $this->assertEquals($expect, $actual);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_count_pushNThenShift_returnsNMinusOne(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = count($data) - 1;
        $list->shift();
        $this->assertCount($expect, $list);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_first_sizeN_returnsFirstValue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = reset($data);
        $actual = $list->first();
        $this->assertEquals($expect, $actual);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_count_pushNThenFirst_returnsN(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = count($data);
        $list->first();
        $this->assertCount($expect, $list);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_lasst_sizeN_returnsFirstValue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = end($data);
        $actual = $list->last();
        $this->assertEquals($expect, $actual);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_count_pushNThenLast_returnsN(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $expect = count($data);
        $list->last();
        $this->assertCount($expect, $list);
    }


    /**
     * @dataProvider provide_rangeZeroToN
     */
    function test_offsetExists_rangeZeroToN_returnsTrue(array $data) {
        $list = $this->instance();
        $n = count($data);
        array_walk($data, [$list, 'push']);

        $expect = $n > 0;
        for ($i = 0; $i < $n; $i++) {
            $this->assertEquals($expect, $list->offsetExists($i));
        }
    }


    /**
     * @dataProvider provide_rangeZeroToN
     */
    function test_offsetExists_forN_returnsFalse(array $data) {
        $list = $this->instance();
        $n = count($data);
        array_walk($data, [$list, 'push']);
        $this->assertFalse($list->offsetExists($n));
    }


    /**
     * @dataProvider provide_rangeZeroToN
     */
    function test_offsetExists_anyNegative_returnsFalse(array $data) {
        $list = $this->instance();
        $n = count($data) * 1;
        $this->assertFalse($list->offsetExists($n));
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_offsetGet_rangeOneToN_returnsValue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);
        $n = count($data);
        for ($i = 0; $i < $n; ++$i) {
            $expect = $i;
            $actual = $list->offsetGet($i);
            $this->assertEquals($expect, $actual);
        }
    }


    function test_seek_negative_throwsException() {
        $this->setExpectedException('\Collections\IndexException');
        $list = $this->instance();
        $list->seek(-1);
    }


    /**
     * @dataProvider provide_rangeZeroToN
     */
    function test_seek_toN_throwsException(array $data) {
        $this->setExpectedException('\Collections\IndexException');
        $list = $this->instance();
        array_walk($data, [$list, 'push']);
        $n = count($data);
        $list->seek($n);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_current_seekOneToN_returnsN(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);
        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            $list->seek($i);
            $expect = $i;
            $actual = $list->current();
            $this->assertEquals($expect, $actual);
        }
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_key_seekN_returnsN(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);
        $n = count($data);
        for ($i = 0; $i < $n; $i++) {
            $list->seek($i);
            $expect = $i;
            $actual = $list->key();
            $this->assertEquals($expect, $actual);
        }
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_seek_toN_returnsValue(array $data) {
        $list = $this->instance();
        foreach ($data as $value) {
            $list->push($value * 2);
        }
        $n = count($data);
        for ($i = 0; $i < $n; ++$i) {
            $expect = $i * 2;
            $actual = $list->seek($i);
            $this->assertEquals($expect, $actual);
        }
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_seek_toNMinusOne_returnsNMinusOneValue(array $data) {
        $list = $this->instance();
        foreach ($data as $value) {
            $list->push($value * 2);
        }
        $n = count($data);
        for ($i = 1; $i < $n; ++$i) {
            $expect = ($i - 1) * 2;
            $actual = $list->seek($i-1);
            $this->assertEquals($expect, $actual);
        }
    }


    function test_seek_toMiddleFromBeginning_returnsMiddleValue() {
        $list = $this->instance();
        $n = 6;
        for ($i = 0; $i < $n; ++$i) {
            $list->push($i*13);
        }
        $list->seek(0);
        $expect = 3 * 13;
        $actual = $list->seek(3);
        $this->assertEquals($expect, $actual);
    }


    function test_seek_toMiddleFromEnd_returnsMiddleValue() {
        $list = $this->instance();
        $n = 6;
        for ($i = 0; $i < $n; ++$i) {
            $list->push($i*13);
        }
        $list->seek(5);
        $expect = 3 * 13;
        $actual = $list->seek(3);
        $this->assertEquals($expect, $actual);
    }


    function test_valid_empty_returnsFalse() {
        $list = $this->instance();
        $this->assertFalse($list->valid());
    }


    function test_valid_emptyAfterRewind_returnsFalse() {
        $list = $this->instance();
        $list->rewind();
        $this->assertFalse($list->valid());
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_valid_pushNThenRewind_returnsTrue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);
        $list->rewind();
        $this->assertTrue($list->valid());
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_valid_pushN_returnsTrue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);
        $list->rewind();
        $this->assertTrue($list->valid());
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_valid_nextN_returnsTrue(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        $list->rewind();
        for ($i = 0; $i < count($data); $i++) {
            $this->assertTrue($list->valid());
            $list->next();
        }
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_key_iterateN_returnsN(array $data) {
        $list = $this->instance();
        foreach ($data as $value) {
            $list->push($value * 2);
        }
        array_walk($data, [$list, 'push']);

        $list->rewind();
        for ($i = 0; $i < count($data); $i++) {
            $expect = $i;
            $actual = $list->key();
            $this->assertEquals($expect, $actual);
            $list->next();
        }
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_current_iterateN_returnsN(array $data) {
        $list = $this->instance();
        foreach ($data as $value) {
            $list->push($value * 2);
        }

        $list->rewind();
        for ($i = 0; $i < count($data); $i++) {
            $expect = $i * 2;
            $actual = $list->current();
            $this->assertEquals($expect, $actual);
            $list->next();
        }
    }


    function test_offsetGet_empty_throwsException() {
        $this->setExpectedException('\Collections\IndexException');
        $list = $this->instance();
        $list[0];
    }


    function test_offsetSet_emptyNonNullOffset_throwsException() {
        $this->setExpectedException('\Collections\IndexException');
        $list = $this->instance();
        $list[0] = 1;
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_offsetGet_offsetSetNThenOffsetGet_returnsN(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        for ($i = 0; $i < count($data); $i++) {
            $list[$i] = $i * 2;
        }
        $list->rewind();
        for ($i = 0; $i < count($data); $i++) {
            $expect = $i * 2;
            $actual = $list[$i];
            $this->assertEquals($expect, $actual);
        }
    }


    function test_insertBefore_empty_throwsException() {
        $this->setExpectedException('\Collections\IndexException');

        $list = $this->instance();
        $list->insertBefore(0, 0);
    }


    function test_insertAfter_empty_throwsException() {
        $this->setExpectedException('\Collections\IndexException');

        $list = $this->instance();
        $list->insertAfter(0, 0);
    }


    function test_count_insertBeforeFirst_increasesSize() {
        $list = $this->instance();
        $list->push(0);
        $before = count($list);
        $list->insertBefore(0, 0);
        $after = count($list);
        $this->assertGreaterThan($before, $after);
    }


    function test_first_insertBeforeFirst_returnsNew() {
        $list = $this->instance();
        $list->push(0);
        $list->insertBefore(0, 1);
        $expect = 1;
        $actual = $list->first();
        $this->assertEquals($expect, $actual);
    }


    function test_last_insertBeforeOnly_returnsOriginal() {
        $list = $this->instance();
        $list->push(0);
        $list->insertBefore(0, 1);
        $expect = 0;
        $actual = $list->last();
        $this->assertEquals($expect, $actual);
    }


    function test_count_insertAfterOnly_increasesSize() {
        $list = $this->instance();
        $list->push(0);
        $before = count($list);
        $list->insertAfter(0, 0);
        $after = count($list);
        $this->assertGreaterThan($before, $after);
    }


    function test_first_insertAfterLast_returnsOriginal() {
        $list = $this->instance();
        $list->push(0);
        $list->insertAfter(0, 1);
        $expect = 0;
        $actual = $list->first();
        $this->assertEquals($expect, $actual);
    }


    function test_last_insertAfterOnly_returnsNew() {
        $list = $this->instance();
        $list->push(0);
        $list->insertAfter(0, 1);
        $expect = 1;
        $actual = $list->last();
        $this->assertEquals($expect, $actual);
    }


    function test_offsetUnset_empty_doesNotThrow() {
        $list = $this->instance();
        unset($list[0]);
    }


    function test_count_offsetUnset_decreasesSize() {
        $list = $this->instance();
        $list->push(0);
        $before = count($list);
        unset($list[0]);
        $after = count($list);
        $this->assertLessThan($before, $after);
    }


    function test_offsetGet_offsetUnsetN_returnsNewN() {
        $list = $this->instance();
        $list->push(0);
        $list->push(1);
        unset($list[0]);
        $expect = 1;
        $actual = $list[0];
        $this->assertEquals($expect, $actual);
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_clone_alteringValues_doesNotChangeOriginal(array $data) {
        $list = $this->instance();
        array_shift($data);
        array_walk($data, [$list, 'push']);

        $clone = clone $list;
        foreach ($clone as $key => $value) {
            $expect = $value * 2;
            $clone[$key] = $expect;
            $actual = $list[$key];
            $this->assertNotEquals($expect, $actual);
        }
    }


    function test_tail_empty_throwsException() {
        $this->setExpectedException('\Collections\EmptyException');

        $list = $this->instance();
        $list->tail();
    }


    /**
     * @dataProvider provide_rangeOneToN
     */
    function test_tail_notEmpty_returnsTail(array $data) {
        $list = $this->instance();
        array_walk($data, [$list, 'push']);

        array_shift($data);
        $expect = $data;
        $actual = $list->tail()->toArray();
        $this->assertEquals($expect, $actual);
    }


    function test_current_prevfromLast_returnsPrevFromLastValue() {
        $list = $this->instance();
        $list->push(0);
        $list->push(1);
        $list->push(2);
        $list->prev();
        $expect = 1;
        $actual = $list->current();
        $this->assertEquals($expect, $actual);
    }


}