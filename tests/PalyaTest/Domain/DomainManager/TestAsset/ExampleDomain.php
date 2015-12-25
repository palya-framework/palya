<?php

namespace PalyaTest\Domain\DomainManager\TestAsset;

use Palya\Domain\DomainManager\Provider\DomainConfigProviderInterface;
use Palya\Domain\DomainManager\Provider\ServiceLocatorConfigProviderInterface;
use Palya\Domain\DomainManager\Provider\SubscriberConfigProviderInterface;

class ExampleDomain implements
    DomainConfigProviderInterface,
    ServiceLocatorConfigProviderInterface,
    SubscriberConfigProviderInterface
{
    public function getDomainConfig()
    {
        return [
            'abc' => [
                'def' => 'ghi'
            ]
        ];
    }

    public function getServiceLocatorConfig()
    {
        return [
            'aliases' => [
                'exampleAlias' => 'exampleService'
            ],
            'factories' => [
                'exampleFactory' => function () { return 'exampleFactory'; }
            ],
            'services' => [
                'exampleService' => function () { return 'exampleService'; }
            ],
        ];
    }

    public function getSubscriberConfig()
    {
        return [
            'exampleService1' => ['exampleEvent1'],
            'exampleService2' => ['exampleEvent2'],
            'exampleService3' => ['exampleEvent3']
        ];
    }
}
