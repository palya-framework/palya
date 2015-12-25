<?php

/**
 * A message stream.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

class MessageStream extends \SplPriorityQueue
{
    /**
     * The serial.
     * @var int
     */
    protected $serial = PHP_INT_MAX;

    /**
     * Pushes a message to the stream. Insertion order is preserved by using
     * a large serial that is counted down with each push.
     * @param MessageInterface $message
     */
    public function push(MessageInterface $message)
    {
        $this->insert($message, $this->serial--);
    }
}
