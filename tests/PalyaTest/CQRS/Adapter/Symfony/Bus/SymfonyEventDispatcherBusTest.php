<?php

namespace PalyaTest\CQRS\Adapter\Symfony\Bus;

use Palya\CQRS\Adapter\Symfony\Bus\SymfonyEventDispatcherBus;
use Palya\CQRS\Bus\MessageStream;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @group cqrs
 */
class SymfonyEventDispatcherBusTest extends \PHPUnit_Framework_TestCase
{
    public function testPublishAddsMessagesToQueue()
    {
        $stream = new MessageStream();
        $stream->push(new Message());

        $bus = new SymfonyEventDispatcherBus(new EventDispatcher());
        $bus->publish($stream);

        $messages = $this->getObjectAttribute($bus, 'messages');
        $this->assertCount(1, $messages);
    }

    public function testCommitPublishesAllMessages()
    {
        $stream = new MessageStream();
        $stream->push(new Message());
        $stream->push(new Message());

        $bus = new SymfonyEventDispatcherBus(new EventDispatcher());
        $bus->publish($stream);
        $bus->commit();

        $messages = $this->getObjectAttribute($bus, 'messages');
        $this->assertTrue($messages->isEmpty());
    }

    public function testCommitDispatchesMessageProperly()
    {
        $stream = new MessageStream();
        $stream->push(new Message());
        $handler = new MessageHandler();

        $bus = new SymfonyEventDispatcherBus(new EventDispatcher());
        $bus->subscribe($handler);

        $bus->publish($stream);
        $bus->commit();

        $this->assertEquals('abc', $handler->getName());
    }
}
