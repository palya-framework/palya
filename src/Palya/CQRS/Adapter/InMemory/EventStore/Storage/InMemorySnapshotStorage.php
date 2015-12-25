<?php

/**
 * An in-memory snapshot storage based on an internal array. Useful for testing
 * purposes.
 *
 * @package   Palya\CQRS\Adapter\InMemory\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\InMemory\EventStore\Storage;

use Palya\CQRS\EventStore\EventProviderInterface;
use Palya\CQRS\EventStore\Storage\SnapshotStorageInterface;
use Ramsey\Uuid\UuidInterface;

class InMemorySnapshotStorage implements SnapshotStorageInterface
{
    /**
     * The snapshots.
     * @var array
     */
    protected $snapshots;

    /**
     * The constructor. Initializes the snapshots.
     */
    public function __construct()
    {
        $this->snapshots = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getSnapshot(UuidInterface $id)
    {
        if (!isset($this->snapshots[$id->toString()])) {
            return null;
        }

        return $this->snapshots[$id->toString()];
    }

    /**
     * {@inheritdoc}
     */
    public function saveSnapshot(EventProviderInterface $eventProvider)
    {
        $this->snapshots[$eventProvider->getId()->toString()] = $eventProvider;
    }
}
