<?php

/*
 * This file is part of the Nurschool project.
 *
 * (c) Nurschool <https://github.com/abbarrasa/nurschool>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Nurschool\Common\Domain\Event\Exception;

use Nurschool\Common\Domain\Event\DomainEvent;

final class EventNotRegisteredException extends \RuntimeException
{
    public function __construct(DomainEvent $event)
    {
        $eventClass = get_class($event);

        parent::__construct("The event <$eventClass> hasn't a event listener associated");
    }
}