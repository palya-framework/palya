<?php

/**
 * An abstract query.
 *
 * @package   Palya\Domain\Query
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\Query;

abstract class AbstractQuery implements QueryInterface
{
    /**
     * By whom this query is issued.
     * @var mixed
     */
    protected $issuedBy;

    /**
     * Returns by whom this query is issued.
     * @return mixed By whom this query is issued.
     */
    public function getIssuedBy()
    {
        return $this->issuedBy;
    }

    /**
     * Sets by whom this query is issued.
     * @param mixed $issuedBy By whom this query is issued.
     * @return AbstractQuery
     */
    public function setIssuedBy($issuedBy)
    {
        $this->issuedBy = $issuedBy;
        return $this;
    }
}
