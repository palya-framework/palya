<?php

/**
 * The interface for a bus.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

use Palya\CQRS\UnitOfWorkInterface;

interface BusInterface extends UnitOfWorkInterface
{
    /**
     * Publishes the message stream to the bus.
     * @param MessageStream $messageStream The message stream.
     */
    public function publish(MessageStream $messageStream);
}
