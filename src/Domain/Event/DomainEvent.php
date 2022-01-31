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


use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class DomainEvent
{
    private UuidInterface $eventId;
    private string $aggregateId;
    protected array $body;

    /** @var \DateTime|\DateTimeInterface */
    private $occurredOn;

    public function __construct(string $aggregateId, array $body = [], ?UuidInterface $eventId = null, ?\DateTimeInterface $occurredOn = null)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId ?: Uuid::uuid4();
        $this->body = $body;
        $this->occurredOn = $occurredOn ?: new \DateTime();
    }

    abstract public static function fromPrimitives(
        string $aggregateId,
        array $body = [],
        string $eventId = null,
        string $occurredOn = null
    ): self;

    abstract public static function eventName(): string;

    abstract public function toPrimitives(): array;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function eventId(): UuidInterface
    {
        return $this->eventId;
    }

    public function occurredOn(): \DateTimeInterface
    {
        return $this->occurredOn;
    }
}