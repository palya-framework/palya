<?php

/**
 * A serialization handler for UUIDs.
 *
 * @package   Palya\CQRS\Adapter\JMS\Serializer\Handler
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS\Adapter\JMS\Serializer\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use Ramsey\Uuid\Uuid;

class UuidHandler implements SubscribingHandlerInterface
{
    /**
     * Serializes the uuid.
     * @param VisitorInterface $visitor
     * @param Uuid $uuid
     * @return string
     */
    public function serialize(VisitorInterface $visitor, Uuid $uuid)
    {
        return $uuid->toString();
    }

    /**
     * De-serializes the uuid.
     * @param VisitorInterface $visitor
     * @param string $name
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function deserialize(VisitorInterface $visitor, $name)
    {
        return Uuid::fromString($name);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'array',
                'type' => 'Ramsey\Uuid\Uuid',
                'method' => 'serialize'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'array',
                'type' => 'Ramsey\Uuid\Uuid',
                'method' => 'deserialize'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'Ramsey\Uuid\Uuid',
                'method' => 'serialize'
            ],
            [
                'direction' => GraphNavigator::DIRECTION_DESERIALIZATION,
                'format' => 'json',
                'type' => 'Ramsey\Uuid\Uuid',
                'method' => 'deserialize'
            ]
        ];
    }
}
