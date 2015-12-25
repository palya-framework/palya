<?php

namespace PalyaTest\Domain\EventPublisher\TestAsset;

use Palya\Domain\EventPublisher\SubscriberInterface;

class MultipleSubscriber implements SubscriberInterface
{
    protected $onExampleCalled = 0;

    protected $onTestCalled = 0;

    public function onExample()
    {
        $this->onExampleCalled++;
    }

    public function onTest()
    {
        $this->onTestCalled++;
    }

    public function isOnExampleCalled()
    {
        return 0 < $this->onExampleCalled;
    }

    public function howOftenIsOnExampleCalled()
    {
        return $this->onExampleCalled;
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
            ['example', [$this, 'onExample']],
            ['test', [$this, 'onTest']],
            ['test', [$this, 'onTest']]
        ];
    }
}
