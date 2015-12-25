<?php

namespace PalyaTest\CQRS\Adapter\Symfony\Bus;

use Palya\CQRS\Bus\MessageInterface;

class Message implements MessageInterface
{
    public function getId()
    {
    }

    public function getName()
    {
        return 'abc';
    }
}
