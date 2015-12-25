<?php

/**
 * The interface for a message.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

interface MessageInterface
{
    /**
     * Returns the name of the message.
     * @return string
     */
    public function getName();
}
