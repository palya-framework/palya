<?php

/**
 * An in-memory event storage based on an internal array. Useful for testing
 * purposes.
 *
 * @package   Palya\CQRS\Adapter\InMemory\EventStore\Storage
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\InMemory\EventStore\Storage;

use Palya\CQRS\Bus\AbstractMessage;
use Palya\CQRS\Event\EventStream;
use Palya\CQRS\EventStore\Storage\EventStorageInterface;
use Ramsey\Uuid\UuidInterface;

class InMemoryEventStorage implements EventStorageInterface
{
    /**
     * The committed events.
     * @var AbstractMessage[]
     */
    protected $committedEvents;

    /**
     * The constructor. Initializes the committed events.
     */
    public function __construct()
    {
        $this->committedEvents = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getCommittedEventStream(UuidInterface $id, $version = 0)
    {
        $eventStream = new EventStream($id);

        if (isset($this->committedEvents[$id->toString()])) {
            foreach ($this->committedEvents[$id->toString()] as $event) {
                $eventStream->push($event);
            }
        }

        return $eventStream;
    }

    /**
     * {@inheritdoc}
     */
    public function saveUncommittedEventStream(EventStream $eventStream)
    {
        foreach ($eventStream as $event) {
            $this->committedEvents[$eventStream->getEventProviderId()->toString()][] = $event;
        }
    }
}
