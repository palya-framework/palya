<?php

namespace PalyaTest\CQRS;

use Palya\CQRS\Adapter\InMemory\EventStore\IdentityMap\InMemoryIdentityMap;
use Palya\CQRS\Adapter\InMemory\EventStore\Storage\InMemoryEventStorage;
use Palya\CQRS\Adapter\InMemory\EventStore\Storage\InMemorySnapshotStorage;
use Palya\CQRS\Bus\NullBus;
use Palya\CQRS\EventStore\EventStore;
use PalyaTest\CQRS\TestAsset\Repository\CustomerRepository;
use PalyaTest\CQRS\TestAsset\Repository\CustomerSnapshotRepository;

/**
 * @group cqrs
 */
class InMemoryEventStoreTest extends AbstractEventStoreTest
{
    protected function setUp()
    {
        $this->eventStore = new EventStore(
            new InMemoryEventStorage(),
            new NullBus(),
            new InMemoryIdentityMap()
        );

        $snapshotStorage = new InMemorySnapshotStorage();

        $this->customerRepository = new CustomerRepository($this->eventStore);
        $this->customerSnapshotRepository = new CustomerSnapshotRepository($this->eventStore, $snapshotStorage);
    }
}
