<?php

namespace PalyaTest\CQRS\Bus;

use Palya\CQRS\Bus\MessageStream;

/**
 * @group cqrs
 */
class MessageStreamTest extends \PHPUnit_Framework_TestCase
{
    public function testPushPreservesInsertionOrder()
    {
        $stream = new MessageStream();
        $stream->push(new Message(1));
        $stream->push(new Message(2));

        $this->assertEquals(1, $stream->current()->getId());
        $stream->next();
        $this->assertEquals(2, $stream->current()->getId());
    }
}
