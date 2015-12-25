<?php

/**
 * The interface for a query.
 *
 * @package   Palya\Domain\Query
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\Query;

interface QueryInterface
{
    /**
     * Returns by whom this query is issued.
     * @return mixed
     */
    public function getIssuedBy();
}
