<?php

/**
 * An abstract command.
 *
 * @package   Palya\Domain\Command
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\Command;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * By whom this command is issued.
     * @var mixed
     */
    protected $issuedBy;

    /**
     * What or who is affected by this command.
     * @var mixed
     */
    protected $applyTo;

    /**
     * {@inheritdoc}
     */
    public function getIssuedBy()
    {
        return $this->issuedBy;
    }

    /**
     * Sets by whom this command is issued.
     * @param mixed $issuedBy By whom this command is issued.
     * @return AbstractCommand
     */
    public function setIssuedBy($issuedBy)
    {
        $this->issuedBy = $issuedBy;
        return $this;
    }

    /**
     * Returns what or who is affected by this command
     * @return mixed What or who is affected by this command.
     */
    public function getApplyTo()
    {
        return $this->applyTo;
    }

    /**
     * Sets what or who is affected by this command.
     * @param mixed $applyTo What or who is affected by this command.
     * @return AbstractCommand
     */
    public function setApplyTo($applyTo)
    {
        $this->applyTo = $applyTo;
        return $this;
    }
}
