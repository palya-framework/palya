<?php

namespace PalyaTest\CQRS\Adapter\JMS\Serializer\Handler;

use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use Palya\CQRS\Adapter\JMS\Serializer\Handler\UuidHandler;
use Ramsey\Uuid\Uuid;

/**
 * @group cqrs
 */
class UuidHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testSerializeReturnsStringRepresentation()
    {
        $id = Uuid::uuid4();
        $visitor = new JsonSerializationVisitor(new CamelCaseNamingStrategy());

        $this->assertEquals($id->toString(), (new UuidHandler())->serialize($visitor, $id));
    }

    public function testDeserializeConstructsUuidFromString()
    {
        $id = '447cbdba-0aed-485d-8b4c-c4a196129d3e';
        $visitor = new JsonSerializationVisitor(new CamelCaseNamingStrategy());

        $this->assertEquals($id, (new UuidHandler())->deserialize($visitor, $id));
    }
}
