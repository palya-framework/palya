<?php

/**
 * An abstract repository for an event provider.
 *
 * @package   Palya\CQRS\Repository
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Repository;

use Palya\CQRS\EventStore\EventProviderInterface;
use Palya\CQRS\EventStore\EventStoreInterface;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractEventProviderRepository implements EventProviderRepositoryInterface
{
    /**
     * The event store
     * @var EventStoreInterface
     */
    protected $eventStore;

    /**
     * The constructor. Initializes the event store.
     * @param EventStoreInterface $eventStore The event store.
     */
    public function __construct(EventStoreInterface $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * {@inheritdoc}
     */
    public function findById(UuidInterface $id)
    {
        $eventStream = $this->eventStore->getEventStream($id);

        if ($eventStream->isEmpty()) {
            return null;
        }

        $eventProvider = $this->createEventProvider();
        $eventProvider->applyCommittedEventStream($eventStream);

        return $eventProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function save(EventProviderInterface $eventProvider)
    {
        $this->eventStore->addEventStream($eventProvider->getUncommittedEventStream());
        $eventProvider->clear();
    }

    /**
     * Returns a prototype for the event provider that is managed by this repository.
     * @return EventProviderInterface
     */
    abstract protected function createEventProvider();
}
