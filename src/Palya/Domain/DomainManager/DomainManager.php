<?php

/**
 * The domain manager.
 *
 * @package   Palya\Domain\DomainManager
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\DomainManager;

use Palya\Domain\DomainManager\Provider\DomainConfigProviderInterface;
use Palya\Domain\DomainManager\Provider\ServiceLocatorConfigProviderInterface;
use Palya\Domain\DomainManager\Provider\SubscriberConfigProviderInterface;
use Palya\Domain\EventPublisher\EventPublisherInterface;
use Palya\Domain\EventPublisher\ServiceLocatorAwareEventPublisher;
use Zend\ServiceManager\ServiceManager;

class DomainManager
{
    /**
     * The config.
     * @var array
     */
    protected $config = [];

    /**
     * The registered domains.
     * @var array
     */
    protected $domains = [];

    /**
     * The registered resources.
     * @var array
     */
    protected $resources = [];

    /**
     * The event publisher.
     * @var ServiceLocatorAwareEventPublisher
     */
    protected $eventPublisher;

    /**
     * The service locator.
     * @var ServiceManager
     */
    protected $serviceLocator;

    /**
     * The constructor. Initializes the event publisher and the service locator.
     * @param ServiceLocatorAwareEventPublisher $eventPublisher The event publisher.
     * @param ServiceManager $serviceLocator The service locator.
     */
    public function __construct(
        ServiceLocatorAwareEventPublisher $eventPublisher,
        ServiceManager $serviceLocator
    ) {
        $this->eventPublisher = $eventPublisher;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Returns the event publisher.
     * @return EventPublisherInterface The event publisher.
     */
    public function getEventPublisher()
    {
        return $this->eventPublisher;
    }

    /**
     * Returns the service locator.
     * @return ServiceManager The service locator.
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Registers a domain.
     * @param mixed $domain The domain.
     * @return DomainManager
     */
    public function registerDomain($domain)
    {
        $this->domains[] = $domain;
        return $this;
    }

    /**
     * Registers a resource.
     * @param string $alias The alias.
     * @param mixed $resource The resource itself.
     * @return DomainManager
     */
    public function registerResource($alias, $resource)
    {
        $this->resources[$alias] = $resource;
        return $this;
    }

    /**
     * Bootstraps the domain resources, the domain providers for every single
     * domain and initializes the config and the event publisher as a service in
     * the service locator.
     * @return DomainManager
     */
    public function bootstrap()
    {
        $this->bootstrapDomainResources();
        $this->bootstrapDomainProviders();

        $this->serviceLocator->setService('palya.config', $this->config);
        $this->serviceLocator->setService('palya.eventPublisher', $this->eventPublisher);

        return $this;
    }

    /**
     * Bootstraps the domain resources.
     */
    protected function bootstrapDomainResources()
    {
        foreach ($this->resources as $alias => $resource) {
            $this->serviceLocator->setService($alias, $resource);
        }
    }

    /**
     * Bootstraps the domain providers for each domain.
     */
    protected function bootstrapDomainProviders()
    {
        foreach ($this->domains as $domain) {
            $this->bootstrapDomainConfig($domain);
            $this->bootstrapServiceLocatorConfig($domain);
            $this->bootstrapSubscriberConfig($domain);
        }
    }

    /**
     * Bootstraps the config for a single domain.
     * @param mixed $domain
     */
    protected function bootstrapDomainConfig($domain)
    {
        if ($domain instanceof DomainConfigProviderInterface) {
            $this->config = array_merge_recursive($this->config, $domain->getDomainConfig());
        }
    }

    /**
     * Bootstraps the config for the service locator for a single domain.
     * @param mixed $domain
     */
    protected function bootstrapServiceLocatorConfig($domain)
    {
        if ($domain instanceof ServiceLocatorConfigProviderInterface) {
            $serviceLocatorConfig = $domain->getServiceLocatorConfig();

            $this->config = array_merge_recursive(
                $this->config,
                ['serviceLocator' => $serviceLocatorConfig]
            );

            if (isset($serviceLocatorConfig['aliases'])) {
                foreach ($serviceLocatorConfig['aliases'] as $alias => $target) {
                    $this->serviceLocator->setAlias($alias, $target);
                }
            }

            if (isset($serviceLocatorConfig['factories'])) {
                foreach ($serviceLocatorConfig['factories'] as $alias => $factory) {
                    $this->serviceLocator->setFactory($alias, $factory);
                }
            }

            if (isset($serviceLocatorConfig['services'])) {
                foreach ($serviceLocatorConfig['services'] as $alias => $service) {
                    $this->serviceLocator->setService($alias, $service);
                }
            }
        }
    }

    /**
     * Bootstraps the config for the domain event subscribers for a single
     * domain.
     * @param $domain
     */
    protected function bootstrapSubscriberConfig($domain)
    {
        if ($domain instanceof SubscriberConfigProviderInterface) {
            $subscriberConfig = $domain->getSubscriberConfig();

            $this->config = array_merge_recursive(
                $this->config,
                ['subscriber' => $subscriberConfig]
            );

            foreach ($subscriberConfig as $alias => $events) {
                foreach ($events as $event) {
                    $this->eventPublisher->subscribeService($event, $alias);
                }
            }
        }
    }
}
