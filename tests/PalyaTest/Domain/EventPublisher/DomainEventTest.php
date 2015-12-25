<?php

namespace PalyaTest\Domain\EventPublisher;

use Palya\Domain\EventPublisher\DomainEvent;

class DomainEventTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNameReturnsNameIfNameIsSet()
    {
        $this->assertEquals('abc', (new DomainEvent('abc'))->getName());
    }

    public function testGetNameReturnsClassNameAsDefault()
    {
        $this->assertEquals(DomainEvent::CLASS, (new DomainEvent())->getName());
    }

    public function testGetParamReturnsNullIfParameterIsNotFound()
    {
        $this->assertNull((new DomainEvent())->getParam('abc'));
    }

    public function testGetParamReturnsValueIfParameterIsFound()
    {
        $this->assertEquals('def', (new DomainEvent())->setParam('abc', 'def')->getParam('abc'));
    }
}
