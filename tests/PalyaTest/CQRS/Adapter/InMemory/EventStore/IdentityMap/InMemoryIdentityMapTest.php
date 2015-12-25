<?php

namespace PalyaTest\CQRS\Adapter\InMemory\EventStore\IdentityMap;

use Palya\CQRS\Adapter\InMemory\EventStore\IdentityMap\InMemoryIdentityMap;
use Palya\CQRS\Event\EventStream;
use Ramsey\Uuid\Uuid;

/**
 * @group cqrs
 */
class InMemoryIdentityMapTest extends \PHPUnit_Framework_TestCase
{
    public function testGetReturnsNullIfEventStreamIsNotPresent()
    {
        $this->assertNull((new InMemoryIdentityMap())->get(Uuid::uuid4()));
    }

    public function testGetReturnsEventStreamIfEventStreamIsPresent()
    {
        $id = Uuid::uuid4();
        $eventStream = new EventStream(Uuid::uuid4());

        $map = new InMemoryIdentityMap();
        $map->set($id, $eventStream);

        $this->assertEquals($eventStream, $map->get($id));
    }

    public function testHasReturnsFalseIfEventStreamIsNotPresent()
    {
        $this->assertFalse((new InMemoryIdentityMap())->has(Uuid::uuid4()));
    }

    public function testHasReturnsTrueIfEventStreamIsPresent()
    {
        $id = Uuid::uuid4();
        $eventStream = new EventStream(Uuid::uuid4());

        $map = new InMemoryIdentityMap();
        $map->set($id, $eventStream);

        $this->assertTrue($map->has($id));
    }

    public function testClearEmptiesIdentityMap()
    {
        $id = Uuid::uuid4();
        $eventStream = new EventStream(Uuid::uuid4());

        $map = new InMemoryIdentityMap();
        $map->set($id, $eventStream);
        $map->clear();

        $this->assertNull($map->get($id));
    }

    public function testIdentityMapImplementsIteratorProperly()
    {
        $map = new InMemoryIdentityMap();
        $map->set(Uuid::uuid4(), new EventStream(Uuid::uuid4()));
        $map->set(Uuid::uuid4(), new EventStream(Uuid::uuid4()));
        $map->set(Uuid::uuid4(), new EventStream(Uuid::uuid4()));

        $i = 0;

        foreach ($map as $item) {
            $i++;
        }

        $this->assertEquals(3, $i);
    }
}
