<?php

/**
 * An abstract domain object.
 *
 * @package   Palya\CQRS\DomainObject
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\DomainObject;

use Palya\CQRS\Event\Event;
use Palya\CQRS\Event\EventStream;
use Palya\CQRS\EventStore\EventProviderInterface;
use Palya\CQRS\EventStore\VersionProvider;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractDomainObject implements EventProviderInterface
{
    /**
     * The id.
     * @var UuidInterface
     */
    protected $id;

    /**
     * The list of uncommitted events.
     * @var Event[]
     */
    protected $uncommittedEvents;

    /**
     * The version provider.
     * @var VersionProvider
     */
    protected $versionProvider;

    /**
     * The constructor.
     */
    protected function __construct()
    {
        $this->uncommittedEvents = [];
        $this->versionProvider = new VersionProvider();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUncommittedEventStream()
    {
        $eventStream = new EventStream($this->getId());

        foreach ($this->uncommittedEvents as $event) {
            $eventStream->push($event);
        }

        return $eventStream;
    }

    /**
     * {@inheritdoc}
     */
    public function applyUncommittedEvent(Event $event)
    {
        $this->handleEvent($event, $this->versionProvider->getNextVersion());
        $this->uncommittedEvents[] = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function applyCommittedEvent(Event $event)
    {
        $this->handleEvent($event, $event->getVersion());
    }

    /**
     * {@inheritdoc}
     */
    public function applyCommittedEventStream(EventStream $eventStream)
    {
        foreach ($eventStream as $event) {
            $this->applyCommittedEvent($event);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->uncommittedEvents = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion()
    {
        return $this->versionProvider->getVersion();
    }

    /**
     * Handles an event.
     * @param Event $event The event.
     * @param int $version The version.
     */
    protected function handleEvent(Event $event, $version)
    {
        $this->{'on' . substr(strrchr(get_class($event), '\\'), 1)}($event);
        $event->setVersion($version);
    }
}
