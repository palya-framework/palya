<?php

namespace PalyaTest\CQRS;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use Palya\CQRS\Adapter\InMemory\EventStore\IdentityMap\InMemoryIdentityMap;
use Palya\CQRS\Adapter\JMS\Serializer\ArrayDeserializationVisitor;
use Palya\CQRS\Adapter\JMS\Serializer\ArraySerializationVisitor;
use Palya\CQRS\Adapter\JMS\Serializer\Handler\UuidHandler;
use Palya\CQRS\Adapter\Mongo\EventStore\Storage\MongoEventStorage;
use Palya\CQRS\Adapter\Mongo\EventStore\Storage\MongoSnapshotStorage;
use Palya\CQRS\Adapter\Mongo\EventStore\Storage\MongoStorageOptions;
use Palya\CQRS\Bus\NullBus;
use Palya\CQRS\EventStore\EventStore;
use PalyaTest\CQRS\TestAsset\Repository\CustomerRepository;
use PalyaTest\CQRS\TestAsset\Repository\CustomerSnapshotRepository;

/**
 * @group cqrs
 */
class MongoEventStoreTest extends AbstractEventStoreTest
{
    protected function setUp()
    {
        $client = new \MongoClient();
        $eventStoreOptions = new MongoStorageOptions('palya-test-events');
        $snapshotStorageOptions = new MongoStorageOptions('palya-test-snapshots');

        // set up a clean state by deleting the database
        $client->selectDB($eventStoreOptions->getDatabase())->drop();
        $client->selectDB($snapshotStorageOptions->getDatabase())->drop();

        $serializationVisitor = new ArraySerializationVisitor(
            new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy())
        );

        $deserializationVisitor = new ArrayDeserializationVisitor(
            new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy())
        );

        $serializer = SerializerBuilder::create()
            ->configureHandlers(function(HandlerRegistry $registry) { $registry->registerSubscribingHandler(new UuidHandler()); })
            ->setSerializationVisitor('array', $serializationVisitor)
            ->setDeserializationVisitor('array', $deserializationVisitor)
            ->build();

        $this->eventStore = new EventStore(
            new MongoEventStorage($client, $eventStoreOptions, $serializer),
            new NullBus(),
            new InMemoryIdentityMap()
        );

        $snapshotStorage = new MongoSnapshotStorage(
            $client,
            $snapshotStorageOptions,
            $serializer
        );

        $this->customerRepository = new CustomerRepository($this->eventStore);
        $this->customerSnapshotRepository = new CustomerSnapshotRepository($this->eventStore, $snapshotStorage);
    }
}
