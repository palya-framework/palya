<?php

/**
 * The interface for a snapshot storage.
 *
 * @package   Palya\CQRS\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore\Storage;

use Palya\CQRS\EventStore\EventProviderInterface;
use Ramsey\Uuid\UuidInterface;

interface SnapshotStorageInterface
{
    /**
     * Returns a snapshot for an event provider.
     * @param UuidInterface $id The id.
     * @return null|EventProviderInterface The event provider or null if none is found.
     */
    public function getSnapshot(UuidInterface $id);

    /**
     * Saves a snapshot of the event provider.
     * @param EventProviderInterface $eventProvider The event provider.
     */
    public function saveSnapshot(EventProviderInterface $eventProvider);
}
