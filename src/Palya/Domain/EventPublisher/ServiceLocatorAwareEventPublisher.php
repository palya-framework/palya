<?php

/**
 * An event publisher that is capable of lazy-loading the subscribers of
 * certain event from a service locator.
 *
 * @package   Palya\Domain\EventPublisher
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher;

use Zend\ServiceManager\ServiceManager;

class ServiceLocatorAwareEventPublisher implements EventPublisherInterface
{
    /**
     * The adapter.
     * @var EventPublisherInterface
     */
    protected $adapter;

    /**
     * The service locator.
     * @var ServiceManager
     */
    protected $serviceLocator;

    /**
     * The events.
     * @var array
     */
    protected $events = [];

    /**
     * The constructor. Initializes the adapter and the service locator.
     * @param EventPublisherInterface $adapter The adapter.
     * @param ServiceManager $serviceLocator The service locator.
     */
    public function __construct(
        EventPublisherInterface $adapter,
        ServiceManager $serviceLocator
    ) {
        $this->adapter = $adapter;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function publish(DomainEventInterface $event)
    {
        $eventName = $event->getName();

        if (isset($this->events[$eventName])) {
            foreach ($this->events[$eventName] as $alias) {
                $this->adapter->subscribe($this->serviceLocator->get($alias));
            }
            unset($this->events[$eventName]);
        }

        $this->adapter->publish($event);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function subscribe(SubscriberInterface $subscriber)
    {
        $this->adapter->subscribe($subscriber);
        return $this;
    }

    /**
     * Subscribes a service.
     * @param string $eventName The name of the event.
     * @param string $alias The alias of the service.
     * @return ServiceLocatorAwareEventPublisher
     */
    public function subscribeService($eventName, $alias)
    {
        $this->events[$eventName][] = $alias;
        return $this;
    }
}
