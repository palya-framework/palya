<?php

namespace PalyaTest\CQRS\Adapter\InMemory\EventStore\Storage;

use Palya\CQRS\Adapter\InMemory\EventStore\Storage\InMemorySnapshotStorage;
use Ramsey\Uuid\Uuid;

/**
 * @group cqrs
 */
class InMemorySnapshotStorageTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSnapshotReturnsNullIfSnapshotIsNotPresent()
    {
        $this->assertNull((new InMemorySnapshotStorage())->getSnapshot(Uuid::uuid4()));
    }

    public function testGetSnapshotReturnsSnapshotIfSnapshotIsPresent()
    {
        $id = Uuid::uuid4();
        $eventProvider = new EventProvider($id);

        $storage = new InMemorySnapshotStorage();
        $storage->saveSnapshot($eventProvider);

        $this->assertEquals($eventProvider, $storage->getSnapshot($id));
    }
}
