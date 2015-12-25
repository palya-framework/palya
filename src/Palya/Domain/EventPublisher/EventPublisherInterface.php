<?php

/**
 * The interface for the event publisher.
 *
 * @package   Palya\Domain\EventPublisher
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher;

interface EventPublisherInterface
{
    /**
     * Publishes the domain event.
     * @param DomainEventInterface $event The domain event.
     * @return EventPublisherInterface
     */
    public function publish(DomainEventInterface $event);

    /**
     * Subscribes an event subscriber.
     * @param SubscriberInterface $subscriber The event subscriber.
     * @return EventPublisherInterface
     */
    public function subscribe(SubscriberInterface $subscriber);
}
