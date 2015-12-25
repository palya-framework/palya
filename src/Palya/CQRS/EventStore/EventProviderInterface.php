<?php

/**
 * An interface for an event provider.
 *
 * @package   Palya\CQRS\EventStore
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore;

use Palya\CQRS\Event\Event;
use Palya\CQRS\Event\EventStream;
use Ramsey\Uuid\UuidInterface;

interface EventProviderInterface
{
    /**
     * Returns the id.
     * @return UuidInterface The id.
     */
    public function getId();

    /**
     * Returns a stream of uncommitted events.
     * @return EventStream The stream of uncommitted events.
     */
    public function getUncommittedEventStream();

    /**
     * Applies an event by adding it to the stream of uncommitted events.
     * @param Event $event The event.
     */
    public function applyUncommittedEvent(Event $event);

    /**
     * Applies a committed event.
     * @param Event $event The event.
     */
    public function applyCommittedEvent(Event $event);

    /**
     * Applies the stream of committed events.
     * @param EventStream $eventStream The stream of committed events.
     */
    public function applyCommittedEventStream(EventStream $eventStream);

    /**
     * Removes all the uncommitted events from the event provider.
     */
    public function clear();

    /**
     * Returns the version of the event provider.
     * @return int
     */
    public function getVersion();
}
