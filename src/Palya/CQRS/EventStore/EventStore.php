<?php

/**
 * The actual event store. Offers the possibilities to add and event provider
 * to the event store and retrieve one by an identifier.
 *
 * @package   Palya\CQRS\EventStore
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\EventStore;

use Palya\CQRS\Bus\BusInterface;
use Palya\CQRS\Bus\MessageStream;
use Palya\CQRS\Event\EventStream;
use Palya\CQRS\EventStore\IdentityMap\IdentityMapInterface;
use Palya\CQRS\EventStore\Storage\EventStorageInterface;
use Palya\CQRS\TransactionalInterface;
use Ramsey\Uuid\UuidInterface;

class EventStore implements EventStoreInterface
{
    /**
     * The event storage.
     * @var EventStorageInterface
     */
    protected $eventStorage;

    /**
     * The event bus.
     * @var BusInterface
     */
    protected $eventBus;

    /**
     * The identity map.
     * @var IdentityMapInterface
     */
    protected $identityMap;

    /**
     * The constructor. Initializes the event storage, the event bus and the identity map.
     * @param EventStorageInterface $eventStorage The event storage.
     * @param BusInterface $eventBus The event bus.
     * @param IdentityMapInterface $identityMap The identity map.
     */
    public function __construct(
        EventStorageInterface $eventStorage,
        BusInterface $eventBus,
        IdentityMapInterface $identityMap
    ) {
        $this->eventStorage = $eventStorage;
        $this->eventBus = $eventBus;
        $this->identityMap = $identityMap;
    }

    /**
     * {@inheritdoc}
     */
    public function addEventStream(EventStream $eventStream)
    {
        $this->identityMap->set($eventStream->getEventProviderId(), $eventStream);
    }

    /**
     * {@inheritdoc}
     */
    public function getEventStream(UuidInterface $id, $version = 0)
    {
        if ($this->identityMap->has($id)) {
            return $this->identityMap->get($id);
        }

        return $this->eventStorage->getCommittedEventStream($id, $version);
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        if ($this->eventStorage instanceof TransactionalInterface) {
            $this->eventStorage->beginTransaction();
        }

        foreach ($this->identityMap as $eventStream) {
            $this->eventStorage->saveUncommittedEventStream($eventStream);
            $this->eventBus->publish(new MessageStream($eventStream));
        }

        if ($this->eventStorage instanceof TransactionalInterface) {
            $this->eventStorage->commit();
        }

        $this->eventBus->commit();
        $this->identityMap->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
        if ($this->eventStorage instanceof TransactionalInterface) {
            $this->eventStorage->rollback();
        }
    }
}
