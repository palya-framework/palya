<?php

/**
 * A generic domain event.
 *
 * @package   Palya\Domain\EventPublisher
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher;

class DomainEvent implements DomainEventInterface
{
    /**
     * The name.
     * @var string
     */
    protected $name;

    /**
     * The parameters.
     * @var array
     */
    protected $params = [];

    /**
     * The constructor. Initializes the name.
     * @param null|string $name The name.
     */
    public function __construct($name = null)
    {
        $this->name = $name;
    }

    /**
     * Returns the name.
     * @return string The name.
     */
    public function getName()
    {
        if (isset($this->name)) {
            return $this->name;
        } else {
            return static::CLASS;
        }
    }

    /**
     * Returns a parameter of the domain event.
     * @param string $name The name of the parameter.
     * @return null|mixed The parameter or null if it's not found.
     */
    public function getParam($name)
    {
        return isset($this->params[$name]) ? $this->params[$name] : null;
    }

    /**
     * Sets a parameter of the domain event.
     * @param string $name The name of the parameter.
     * @param mixed $value The value of the parameter.
     * @return DomainEvent
     */
    public function setParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }
}
