<?php

/**
 * The adapter for the event dispatcher that uses the event dispatcher from
 * the Symfony package.
 *
 * @package   Palya\Domain\EventPublisher\Adapter
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher\Adapter;

use Palya\Domain\EventPublisher\DomainEventInterface;
use Palya\Domain\EventPublisher\EventPublisherInterface;
use Palya\Domain\EventPublisher\SubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

class SymfonyEventDispatcherAdapter implements EventPublisherInterface
{
    /**
     * The event dispatcher.
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * The constructor. Initializes the event dispatcher.
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(DomainEventInterface $event)
    {
        $this->eventDispatcher->dispatch($event->getName(), new GenericEvent($event));
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscriptions() as $subscription) {
            $this->eventDispatcher->addListener(
                $subscription[0],
                function (GenericEvent $event) use ($subscription) {
                    call_user_func($subscription[1], $event->getSubject());
                },
                isset($subscription[2]) ? $subscription[2] : 0
            );
        }

        return $this;
    }
}
