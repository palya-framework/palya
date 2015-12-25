<?php

namespace PalyaTest\CQRS\Adapter\InMemory\EventStore\Storage;

use Palya\CQRS\Adapter\InMemory\EventStore\Storage\InMemoryEventStorage;
use Palya\CQRS\Event\Event;
use Palya\CQRS\Event\EventStream;
use Ramsey\Uuid\Uuid;

/**
 * @group cqrs
 */
class InMemoryEventStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCommittedEventStreamReturnsEmptyEventStreamIfEventStreamIsNotPresent()
    {
        $this->assertCount(0, (new InMemoryEventStorage())->getCommittedEventStream(Uuid::uuid4()));
    }

    public function testGetCommittedEventStreamReturnsStreamOfEvents()
    {
        $id = Uuid::uuid4();

        $eventStream = new EventStream($id);
        $eventStream->push(new Event(Uuid::uuid4()));
        $eventStream->push(new Event(Uuid::uuid4()));
        $eventStream->push(new Event(Uuid::uuid4()));

        $storage = new InMemoryEventStorage();
        $storage->saveUncommittedEventStream($eventStream);

        $this->assertCount(3, $storage->getCommittedEventStream($id));
    }
}
