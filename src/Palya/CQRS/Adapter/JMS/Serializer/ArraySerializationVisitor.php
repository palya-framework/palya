<?php

/**
 * A visitor to serialize an object structure into an array.
 *
 * @package   Palya\CQRS\Adapter\JMS\Serializer
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\JMS\Serializer;

use JMS\Serializer\GenericSerializationVisitor;

class ArraySerializationVisitor extends GenericSerializationVisitor
{
    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        return $this->getRoot();
    }
}
