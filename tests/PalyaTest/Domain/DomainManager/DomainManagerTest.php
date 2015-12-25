<?php

namespace PalyaTest\Domain\DomainManager;

use Palya\Domain\DomainManager\DomainManager;
use Palya\Domain\EventPublisher\ServiceLocatorAwareEventPublisher;
use PalyaTest\Domain\DomainManager\TestAsset\ExampleDomain;
use Zend\ServiceManager\ServiceManager;

class DomainManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testBootstrapRecognizesResources()
    {
        $resource = new \stdClass();

        $eventPublisher = $this
            ->getMockBuilder(ServiceLocatorAwareEventPublisher::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();

        $domainManager = new DomainManager($eventPublisher, $serviceLocator);
        $domainManager->registerResource('abc', $resource);
        $domainManager->bootstrap();

        $this->assertTrue($serviceLocator->has('abc'));
    }

    public function testBootstrapRecognizesDomainConfig()
    {
        $eventPublisher = $this
            ->getMockBuilder(ServiceLocatorAwareEventPublisher::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();

        $domainManager = new DomainManager($eventPublisher, $serviceLocator);
        $domainManager->registerDomain(new ExampleDomain());
        $domainManager->bootstrap();

        $this->assertTrue($serviceLocator->has('palya.config'));
        $this->assertArrayHasKey('abc', $serviceLocator->get('palya.config'));
    }

    public function testBootstrapRecognizesServiceLocatorConfig()
    {
        $eventPublisher = $this
            ->getMockBuilder(ServiceLocatorAwareEventPublisher::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceLocator = new ServiceManager();

        $domainManager = new DomainManager($eventPublisher, $serviceLocator);
        $domainManager->registerDomain(new ExampleDomain());
        $domainManager->bootstrap();

        $this->assertTrue($serviceLocator->has('exampleAlias'));
        $this->assertEquals('exampleService', call_user_func($serviceLocator->get('exampleAlias')));

        $this->assertTrue($serviceLocator->has('exampleFactory'));
        $this->assertEquals('exampleFactory', $serviceLocator->get('exampleFactory'));

        $this->assertTrue($serviceLocator->has('exampleService'));
        $this->assertEquals('exampleService', call_user_func($serviceLocator->get('exampleService')));
    }

    public function testBootstrapRecognizesSubscriberConfig()
    {
        $externalEventPublisher = $this
            ->getMockBuilder(ServiceLocatorAwareEventPublisher::CLASS)
            ->disableOriginalConstructor()
            ->getMock();

        $externalEventPublisher
            ->expects($this->at(0))
            ->method('subscribeService')
            ->with('exampleEvent1', 'exampleService1');

        $externalEventPublisher
            ->expects($this->at(1))
            ->method('subscribeService')
            ->with('exampleEvent2', 'exampleService2');

        $externalEventPublisher
            ->expects($this->at(2))
            ->method('subscribeService')
            ->with('exampleEvent3', 'exampleService3');

        $domainManager = new DomainManager($externalEventPublisher, new ServiceManager());
        $domainManager->registerDomain(new ExampleDomain());
        $domainManager->bootstrap();
    }
}
