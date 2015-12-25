<?php

namespace PalyaTest\Domain\EventPublisher\TestAsset;

use Palya\Domain\EventPublisher\SubscriberInterface;

class SimpleSubscriber implements SubscriberInterface
{
    protected $onTestCalled = 0;

    public function onTest()
    {
        $this->onTestCalled++;
    }

    public function isOnTestCalled()
    {
        return 0 < $this->onTestCalled;
    }

    public function howOftenIsOnTestCalled()
    {
        return $this->onTestCalled;
    }

    public function getSubscriptions()
    {
        return [
            ['test', [$this, 'onTest']]
        ];
    }
}
