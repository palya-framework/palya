<?php

namespace PalyaTest\CQRS\Adapter\Zend\Bus;

use Palya\CQRS\Bus\MessageHandlerInterface;
use Palya\CQRS\Bus\MessageInterface;

class MessageHandler implements MessageHandlerInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function handle(MessageInterface $message)
    {
        $this->name = $message->getName();
    }

    public function getMessageSubscriptions()
    {
        return ['def'];
    }
}
