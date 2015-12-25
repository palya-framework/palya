<?php

/**
 * A simple bus utilizing the basic functionality of the Zend event manager
 * component.
 *
 * @package   Palya\CQRS\Adapter\Zend\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\Zend\Bus;

use Palya\CQRS\Bus\AbstractBus;
use Palya\CQRS\Bus\MessageHandlerInterface;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;

class ZendEventManagerBus extends AbstractBus
{
    /**
     * The event manager.
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * The constructor. Initializes the event manager.
     * @param EventManagerInterface $eventManager The event manager.
     */
    public function __construct(EventManagerInterface $eventManager)
    {
        parent::__construct();
        $this->eventManager = $eventManager;
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
        while (!$this->messages->isEmpty()) {
            $message = $this->messages->dequeue();
            $this->eventManager->trigger($message->getName(), $message);
        }
    }

    /**
     * Subscribes the message handler to the bus.
     * @param MessageHandlerInterface $messageHandler The message handler.
     */
    public function subscribe(MessageHandlerInterface $messageHandler)
    {
        foreach ($messageHandler->getMessageSubscriptions() as $subscription) {
            $this->eventManager->attach(
                $subscription,
                function (Event $event) use ($messageHandler) {
                    $messageHandler->handle($event->getTarget());
                }
            );
        }
    }
}
