<?php

namespace PalyaTest\CQRS;

use Doctrine\MongoDB\Connection;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage\DoctrineMongoDBEventStorage;
use Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage\DoctrineMongoDBSnapshotStorage;
use Palya\CQRS\Adapter\DoctrineMongoDB\EventStore\Storage\DoctrineMongoDBStorageOptions;
use Palya\CQRS\Adapter\InMemory\EventStore\IdentityMap\InMemoryIdentityMap;
use Palya\CQRS\Adapter\JMS\Serializer\Handler\UuidHandler;
use Palya\CQRS\Bus\NullBus;
use Palya\CQRS\EventStore\EventStore;
use PalyaTest\CQRS\TestAsset\Repository\CustomerRepository;
use PalyaTest\CQRS\TestAsset\Repository\CustomerSnapshotRepository;

/**
 * @group cqrs
 */
class DoctrineMongoDBEventStoreTest extends AbstractEventStoreTest
{
    protected function setUp()
    {
        $connection = new Connection();
        $eventStoreOptions = new DoctrineMongoDBStorageOptions('palya-test-events');
        $snapshotStorageOptions = new DoctrineMongoDBStorageOptions('palya-test-snapshots');

        // set up a clean state by deleting the database
        $connection->dropDatabase($eventStoreOptions->getDatabase());
        $connection->dropDatabase($snapshotStorageOptions->getDatabase());

        $serializer = SerializerBuilder::create()
            ->configureHandlers(function(HandlerRegistry $registry) { $registry->registerSubscribingHandler(new UuidHandler()); })
            ->build();

        $this->eventStore = new EventStore(
            new DoctrineMongoDBEventStorage($connection, $eventStoreOptions, $serializer),
            new NullBus(),
            new InMemoryIdentityMap()
        );

        $snapshotStorage = new DoctrineMongoDBSnapshotStorage(
            $connection,
            $eventStoreOptions,
            $serializer
        );

        $this->customerRepository = new CustomerRepository($this->eventStore);
        $this->customerSnapshotRepository = new CustomerSnapshotRepository($this->eventStore, $snapshotStorage);
    }
}
