<?php

/**
 * An abstract repository for an event provider that uses a snapshot storage
 * in addition to the event store.
 *
 * @package   Palya\CQRS\Repository
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Repository;

use Palya\CQRS\EventStore\EventStoreInterface;
use Palya\CQRS\EventStore\Storage\SnapshotStorageInterface;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractSnapshotRepository extends AbstractEventProviderRepository
{
    /**
     * @var SnapshotStorageInterface
     */
    protected $snapshotStorage;

    /**
     * The constructor. Initializes the event store and the snapshot storage.
     * @param EventStoreInterface $eventStore The event store.
     * @param SnapshotStorageInterface $snapshotStorage The snapshot storage.
     */
    public function __construct(
        EventStoreInterface $eventStore,
        SnapshotStorageInterface $snapshotStorage
    ) {
        parent::__construct($eventStore);
        $this->snapshotStorage = $snapshotStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(UuidInterface $id)
    {
        $snapshot = $this->snapshotStorage->getSnapshot($id);

        if (!isset($snapshot)) {
            return parent::findById($id);
        }

        $eventStream = $this->eventStore->getEventStream($id, $snapshot->getVersion());
        $snapshot->applyCommittedEventStream($eventStream);

        return $snapshot;
    }
}
