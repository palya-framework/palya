<?php

/**
 * A simple bus utilizing the basic functionality of the Symfony event
 * dispatcher component.
 *
 * @package   Palya\CQRS\Adapter\Symfony\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\Symfony\Bus;

use Palya\CQRS\Bus\AbstractBus;
use Palya\CQRS\Bus\MessageHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;

class SymfonyEventDispatcherBus extends AbstractBus
{
    /**
     * The event dispatcher.
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * The constructor. Initializes the event dispatcher.
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        parent::__construct();
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        while (!$this->messages->isEmpty()) {
            $message = $this->messages->dequeue();
            $this->eventDispatcher->dispatch($message->getName(), new GenericEvent($message));
        }
    }

    /**
     * Subscribes the message handler to the bus.
     * @param MessageHandlerInterface $messageHandler The message handler.
     */
    public function subscribe(MessageHandlerInterface $messageHandler)
    {
        foreach ($messageHandler->getMessageSubscriptions() as $subscription) {
            $this->eventDispatcher->addListener(
                $subscription,
                function (GenericEvent $event) use ($messageHandler) {
                    $messageHandler->handle($event->getSubject());
                }
            );
        }
    }
}
