<?php

/**
 * A unit of work interface to commit and roll back the changes of a business
 * transaction.
 *
 * @package   Palya\CQRS
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS;

interface UnitOfWorkInterface
{
    /**
     * Commits all the changes of a transaction.
     */
    public function commit();

    /**
     * Cancels all the changes of a transaction.
     */
    public function rollback();
}
