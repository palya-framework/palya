<?php

/**
 * A composite event publisher that is publishing and subscribing event for
 * each individual adapter.
 *
 * @package   Palya\Domain\EventPublisher
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher;

class CompositeEventPublisher implements EventPublisherInterface
{
    /**
     * The adapters.
     * @var EventPublisherInterface[]
     */
    protected $adapters;

    /**
     * The constructor. Initializes the adapters.
     * @param EventPublisherInterface[] $adapters The adapters.
     */
    public function __construct(array $adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(DomainEventInterface $event)
    {
        foreach ($this->adapters as $adapter) {
            $adapter->publish($event);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(SubscriberInterface $subscriber)
    {
        foreach ($this->adapters as $adapter) {
            $adapter->subscribe($subscriber);
        }

        return $this;
    }
}
