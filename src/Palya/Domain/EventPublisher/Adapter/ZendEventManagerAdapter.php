<?php

/**
 * The adapter for the event dispatcher that uses the event manager from
 * the Zend package.
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
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class ZendEventManagerAdapter implements EventPublisherInterface
{
    /**
     * The event manager.
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * The constructor. Initializes the event manager.
     * @param EventManagerInterface $eventManager The event manager.
     */
    public function __construct(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(DomainEventInterface $event)
    {
        $this->eventManager->trigger($event->getName(), $event);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(SubscriberInterface $subscriber)
    {
        foreach ($subscriber->getSubscriptions() as $subscription) {
            $this->eventManager->attach(
                $subscription[0],
                function (Event $event) use ($subscription) {
                    call_user_func($subscription[1], $event->getTarget());
                },
                isset($subscription[2]) ? $subscription[2] : 0
            );
        }

        return $this;
    }
}
