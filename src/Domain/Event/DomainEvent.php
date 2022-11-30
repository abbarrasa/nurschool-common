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

namespace Nurschool\Common\Domain\Event;

use DateTimeImmutable;
use DateTimeInterface;

abstract class DomainEvent
{
    protected string $aggregateId;
    private string $occurredOn;

    public function __construct(string $aggregateId, ?DateTimeInterface $occurredOn = null)
    {
        $this->aggregateId = $aggregateId;
        $this->occurredOn = $occurredOn ?
            $occurredOn->format(DateTimeInterface::ATOM) :
            (new \DateTime())->format(DateTimeInterface::ATOM);
    }

    public function getOccurredOn(): \DateTimeInterface
    {
        return new DateTimeImmutable($this->occurredOn);
    }

    abstract public static function eventName(): string;
}