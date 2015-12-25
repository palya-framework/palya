<?php

namespace PalyaTest\Domain\EventPublisher;

use Palya\Domain\EventPublisher\DomainEvent;
use Palya\Domain\EventPublisher\EventPublisherInterface;
use Palya\Domain\EventPublisher\ServiceLocatorAwareEventPublisher;
use PalyaTest\Domain\EventPublisher\TestAsset\SimpleSubscriber;
use Zend\ServiceManager\ServiceManager;

class ServiceLocatorAwareEventPublisherTest extends \PHPUnit_Framework_TestCase
{
    public function testPublishLazyLoadsEventSubscriber()
    {
        $event = new DomainEvent('test');
        $subscriber = new SimpleSubscriber();

        $adapter = $this
            ->getMockBuilder(EventPublisherInterface::CLASS)
            ->getMock();

        $adapter
            ->expects($this->at(0))
            ->method('subscribe')
            ->with($subscriber);

        $adapter
            ->expects($this->at(1))
            ->method('publish')
            ->with($event);

        $serviceLocator = $this
            ->getMockBuilder(ServiceManager::CLASS)
            ->getMock();

        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('abc')
            ->will($this->returnValue($subscriber));

        $serviceLocatorAwareEventPublisher = new ServiceLocatorAwareEventPublisher(
            $adapter, $serviceLocator
        );

        $serviceLocatorAwareEventPublisher
            ->subscribeService('test', 'abc')
            ->publish($event);

        $this->assertAttributeEquals([], 'events', $serviceLocatorAwareEventPublisher);
    }

    public function testSubscribeCallsSubscriberOnAdapter()
    {
        $subscriber = new SimpleSubscriber();

        $adapter = $this
            ->getMockBuilder(EventPublisherInterface::CLASS)
            ->getMock();

        $adapter
            ->expects($this->once())
            ->method('subscribe')
            ->with($subscriber);

        $serviceLocator = $this
            ->getMockBuilder(ServiceManager::CLASS)
            ->getMock();

        $serviceLocatorAwareEventPublisher = new ServiceLocatorAwareEventPublisher(
            $adapter, $serviceLocator
        );

        $serviceLocatorAwareEventPublisher->subscribe($subscriber);
    }

    public function testSubscribeServiceStoresTheEventNameAndTheAliasInternally()
    {
        $adapter = $this
            ->getMockBuilder(EventPublisherInterface::CLASS)
            ->getMock();

        $serviceLocator = $this
            ->getMockBuilder(ServiceManager::CLASS)
            ->getMock();

        $serviceLocatorAwareEventPublisher = new ServiceLocatorAwareEventPublisher(
            $adapter, $serviceLocator
        );

        $serviceLocatorAwareEventPublisher->subscribeService('abc', 'def');

        $this->assertAttributeEquals(['abc' => ['def']], 'events', $serviceLocatorAwareEventPublisher);
    }
}
