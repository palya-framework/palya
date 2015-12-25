<?php

namespace PalyaTest\Domain\EventPublisher\TestAsset;

use Palya\Domain\EventPublisher\SubscriberInterface;

class PrioritySubscriber implements SubscriberInterface
{
    public function onLowTest()
    {
    }

    public function onMidTest()
    {
    }

    public function onHighTest()
    {
    }

    public function getSubscriptions()
    {
        return [
            ['test', [$this, 'onLowTest'], -1000],
            ['test', [$this, 'onMidTest'], 0],
            ['test', [$this, 'onHighTest'], 1000]
        ];
    }
}
