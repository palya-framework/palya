<?php

/**
 * A transactional interface to capture all the changes within a transaction
 * and commit or rollback them with a single transaction.
 *
 * @package   Palya\CQRS
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\CQRS;

interface TransactionalInterface
{
    /**
     * Starts a transaction.
     */
    public function beginTransaction();

    /**
     * Commits all the changes of a transaction.
     */
    public function commit();

    /**
     * Cancels all the changes of a transaction.
     */
    public function rollback();
}
