<?php

namespace PalyaTest\CQRS\Adapter\Zend\Bus;

use Palya\CQRS\Adapter\Zend\Bus\ZendEventManagerBus;
use Palya\CQRS\Bus\MessageStream;
use Zend\EventManager\EventManager;

/**
 * @group cqrs
 */
class ZendEventTest extends \PHPUnit_Framework_TestCase
{
    public function testPublishAddsMessagesToQueue()
    {
        $stream = new MessageStream();
        $stream->push(new Message());

        $bus = new ZendEventManagerBus(new EventManager());
        $bus->publish($stream);

        $messages = $this->getObjectAttribute($bus, 'messages');
        $this->assertCount(1, $messages);
    }

    public function testCommitPublishesAllMessages()
    {
        $stream = new MessageStream();
        $stream->push(new Message());
        $stream->push(new Message());

        $bus = new ZendEventManagerBus(new EventManager());
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

        $bus = new ZendEventManagerBus(new EventManager());
        $bus->subscribe($handler);

        $bus->publish($stream);
        $bus->commit();

        $this->assertEquals('def', $handler->getName());
    }
}
