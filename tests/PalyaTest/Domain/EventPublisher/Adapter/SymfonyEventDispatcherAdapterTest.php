<?php

namespace PalyaTest\Domain\EventPublisher\Adapter;

use Palya\Domain\EventPublisher\Adapter\SymfonyEventDispatcherAdapter;
use Palya\Domain\EventPublisher\DomainEvent;
use PalyaTest\Domain\EventPublisher\TestAsset\MultipleSubscriber;
use PalyaTest\Domain\EventPublisher\TestAsset\PrioritySubscriber;
use PalyaTest\Domain\EventPublisher\TestAsset\SimpleSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SymfonyEventDispatcherAdapterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SymfonyEventDispatcherAdapter
     */
    protected $adapter;

    protected function setUp()
    {
        $this->adapter = new SymfonyEventDispatcherAdapter(new EventDispatcher());
    }

    public function testEventPublisherIsCapableOfSubscribePublish()
    {
        $event = new DomainEvent('test');
        $subscriber = new SimpleSubscriber();

        $this->adapter
            ->subscribe($subscriber)
            ->publish($event);

        $this->assertTrue($subscriber->isOnTestCalled());
    }

    public function testEventPublisherAllowsMultipleSubscriptionsAtOnce()
    {
        $exampleEvent = new DomainEvent('example');
        $testEvent = new DomainEvent('test');

        $multipleSubscriber = new MultipleSubscriber();

        $this->adapter
            ->subscribe($multipleSubscriber)
            ->publish($exampleEvent)
            ->publish($testEvent);

        $this->assertTrue($multipleSubscriber->isOnExampleCalled());
        $this->assertEquals(1, $multipleSubscriber->howOftenIsOnExampleCalled());
        $this->assertTrue($multipleSubscriber->isOnTestCalled());
        $this->assertEquals(2, $multipleSubscriber->howOftenIsOnTestCalled());
    }

    public function testEventPublisherPreserversPriority()
    {
        $event = new DomainEvent('test');

        $prioritySubscriber = $this
            ->getMockBuilder(PrioritySubscriber::CLASS)
            ->setMethods(['onHighTest', 'onMidTest', 'onLowTest'])
            ->getMock();

        $prioritySubscriber
            ->expects($this->at(0))
            ->method('onHighTest');

        $prioritySubscriber
            ->expects($this->at(1))
            ->method('onMidTest');

        $prioritySubscriber
            ->expects($this->at(2))
            ->method('onLowTest');

        $this->adapter
            ->subscribe($prioritySubscriber)
            ->publish($event);
    }
}
