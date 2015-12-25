<?php

/**
 * A visitor to de-serialize an array into an object structure.
 *
 * @package   Palya\CQRS\Adapter\JMS\Serializer
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\JMS\Serializer;

use JMS\Serializer\GenericDeserializationVisitor;

class ArrayDeserializationVisitor extends GenericDeserializationVisitor
{
    /**
     * {@inheritdoc}
     */
    protected function decode($str)
    {
        return $str;
    }
}
