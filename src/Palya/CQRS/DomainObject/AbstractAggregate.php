<?php

/**
 * An abstract aggregate.
 *
 * @package   Palya\CQRS\DomainObject
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\DomainObject;

use Palya\CQRS\Event\Event;
use Palya\CQRS\EventStore\EntityEventProviderInterface;

abstract class AbstractAggregate extends AbstractDomainObject
{
    /**
     * The list of entity event providers.
     * @var EntityEventProviderInterface[]
     */
    protected $entityEventProviders;

    /**
     * {@inheritdoc}
     */
    protected function __construct()
    {
        parent::__construct();
        $this->entityEventProviders = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getUncommittedEventStream()
    {
        $eventStream = parent::getUncommittedEventStream();

        foreach ($this->entityEventProviders as $eventProvider) {
            foreach ($eventProvider->getUncommittedEventStream() as $event) {
                $eventStream->push($event);
            }
        }

        return $eventStream;
    }

    /**
     * {@inheritdoc}
     */
    public function applyCommittedEvent(Event $event)
    {
        $id = $event->getEventProviderId();

        if (isset($this->entityEventProviders[$id->toString()])) {
            $eventProvider = $this->entityEventProviders[$id->toString()];
            $eventProvider->applyCommittedEvent($event);
        } else {
            $this->handleEvent($event, $event->getVersion());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        parent::clear();

        foreach ($this->entityEventProviders as $eventProvider) {
            $eventProvider->clear();
        }
    }

    /**
     * Registers an entity event provider that is managed by this aggregate.
     * @param EntityEventProviderInterface $entityEventProvider The entity event provider.
     */
    protected function registerEntityEventProvider(EntityEventProviderInterface $entityEventProvider)
    {
        $this->entityEventProviders[$entityEventProvider->getId()->toString()] = $entityEventProvider;
    }
}
