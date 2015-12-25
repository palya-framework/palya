<?php

/**
 * An abstract message.
 *
 * @package   Palya\CQRS\Bus
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Bus;

use JMS\Serializer\Annotation as JMS;

abstract class AbstractMessage implements MessageInterface
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return static::CLASS;
    }
}
