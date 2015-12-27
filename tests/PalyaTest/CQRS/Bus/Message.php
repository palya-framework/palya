<?php

namespace PalyaTest\CQRS\Bus;

use Palya\CQRS\Bus\MessageInterface;

class Message implements MessageInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return 'abc';
    }
}
