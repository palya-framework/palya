<?php

/**
 * The interface for an event storage.
 *
 * @package   Palya\CQRS\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore\Storage;

use Palya\CQRS\Event\EventStream;
use Ramsey\Uuid\UuidInterface;

interface EventStorageInterface
{
    /**
     * Returns a stream of committed events. The version is treated as a minimum
     * version and only returns events with this version or greater.
     * @param UuidInterface $id The id.
     * @param int $version
     * @return EventStream The stream of committed events.
     */
    public function getCommittedEventStream(UuidInterface $id, $version = 0);

    /**
     * Saves a stream of uncommitted events.
     * @param EventStream $eventStream The stream of uncommitted events.
     */
    public function saveUncommittedEventStream(EventStream $eventStream);
}
