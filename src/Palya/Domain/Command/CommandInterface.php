<?php

/**
 * The interface for a command.
 *
 * @package   Palya\Domain\Command
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\Command;

interface CommandInterface
{
    /**
     * Returns by whom this command is issued.
     * @return mixed By whom this command is issued.
     */
    public function getIssuedBy();
}
