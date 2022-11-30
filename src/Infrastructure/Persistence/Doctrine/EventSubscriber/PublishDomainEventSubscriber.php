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

namespace Nurschool\Common\Infrastructure\Persistence\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Nurschool\Common\Domain\AggregateRoot;
use Nurschool\Common\Domain\Event\DomainEventDispatcher;

class PublishDomainEventSubscriber implements EventSubscriber
{
    /** @var DomainEventDispatcher */
    private $domainEventDispatcher;

    public function __construct(DomainEventDispatcher $domainEventDispatcher)
    {
        $this->domainEventDispatcher = $domainEventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $event)
    {
        $this->publishDomainEvents($event);
    }

    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->publishDomainEvents($event);
    }

    public function postRemove(LifecycleEventArgs $event)
    {
        $this->publishDomainEvents($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    private function publishDomainEvents(LifecycleEventArgs $event): void
    {
        $object = $event->getObject();
        if ($object instanceof AggregateRoot) {
            $events = $object->pullDomainEvents();
            foreach($events as $event) {
                $this->domainEventDispatcher->dispatch($event);
            }
        }
    }
}