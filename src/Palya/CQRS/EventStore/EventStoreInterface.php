<?php

/**
 * The interface for an event store.
 *
 * @package   Palya\CQRS\EventStore
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore;

use Palya\CQRS\Event\EventStream;
use Palya\CQRS\UnitOfWorkInterface;
use Ramsey\Uuid\UuidInterface;

interface EventStoreInterface extends UnitOfWorkInterface
{
    /**
     * Adds an event stream to the event store.
     * @param EventStream $eventStream The event stream.
     */
    public function addEventStream(EventStream $eventStream);

    /**
     * Returns an event stream associated with the id from the event store. The
     * version is treated as a minimum version and only returns events with this
     * version or greater.
     * @param UuidInterface $id The id.
     * @param int $version The version.
     * @return EventStream
     */
    public function getEventStream(UuidInterface $id, $version = 0);
}
