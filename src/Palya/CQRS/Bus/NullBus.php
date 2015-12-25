<?php

/**
 * A bus that acts as a black hole. All the messages are swallowed and
 * disappear without further notice.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

class NullBus implements BusInterface
{
    /**
     * {@inheritdoc}
     */
    public function publish(MessageStream $messageStream)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function commit()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function rollback()
    {
    }
}
