<?php

/**
 * The interface for an event subscriber.
 *
 * @package   Palya\Domain\EventPublisher
 * @copyright Copyright (c) 2013-2016 developer <email>
 * @author    Daniel Keil <daniel.keil@me.de>
 * @author    Torsten Heinrich <t.heinrich@live.de>
 */

namespace Palya\Domain\EventPublisher;

interface SubscriberInterface
{
    /**
     * Returns the subscriptions. Each subscription must be a array with the
     * first element being the event name, the second being the callback and an
     * optional priority as the third element.
     * @return array The subscriptions.
     */
    public function getSubscriptions();
}
