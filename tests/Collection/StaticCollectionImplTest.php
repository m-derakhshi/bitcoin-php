<?php

declare(strict_types=1);

namespace BitWasp\Bitcoin\Tests\Collection;

use BitWasp\Bitcoin\Collection\StaticBufferCollection;
use BitWasp\Bitcoin\Tests\AbstractTestCase;
use BitWasp\Buffertools\Buffer;

class StaticCollectionImplTest extends AbstractTestCase
{
    public function test_array_access_off_static_buffer_collection_get()
    {
        $collection = new StaticBufferCollection(new Buffer("\x01"));
        $this->assertEquals(new Buffer("\x01"), $collection[0]);
        $this->assertEquals(new Buffer("\x01"), $collection->offsetGet(0));
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function test_array_access_off_static_buffer_collection_get_failure()
    {
        $collection = new StaticBufferCollection(new Buffer("\x01"));
        $collection[20];
    }

    public function test_iterator_static_buffer_collection_current()
    {
        $StaticBufferCollection = new StaticBufferCollection(new Buffer("\x01"), new Buffer("\x02"));
        $this->assertEquals(new Buffer("\x01"), $StaticBufferCollection->current());
    }

    public function test_countable()
    {
        $StaticBufferCollection = new StaticBufferCollection(new Buffer("\x01"), new Buffer("\x02"));
        $this->assertEquals(2, count($StaticBufferCollection));
    }

    public function test_all()
    {
        $all = [new Buffer("\x01"), new Buffer("\x02")];
        $StaticBufferCollection = new StaticBufferCollection(new Buffer("\x01"), new Buffer("\x02"));
        $this->assertEquals($all, $StaticBufferCollection->all());
    }

    public function test_get()
    {
        $StaticBufferCollection = new StaticBufferCollection(new Buffer("\x01"), new Buffer("\x02"));
        $this->assertEquals(new Buffer("\x01"), $StaticBufferCollection[0]);
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function test_get_invalid()
    {
        $StaticBufferCollection = new StaticBufferCollection;
        $StaticBufferCollection[0];
    }
}
