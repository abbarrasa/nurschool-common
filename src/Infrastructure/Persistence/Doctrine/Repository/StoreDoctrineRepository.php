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

namespace Nurschool\Common\Infrastructure\Persistence\Doctrine\Repository;

use Nurschool\Common\Domain\AggregateRoot;
use Nurschool\Common\Domain\Repository\StoreRepository;
use Nurschool\Common\Infrastructure\Persistence\Exception\UnexpectedClassException;

abstract class StoreDoctrineRepository extends DoctrineRepository implements StoreRepository
{
    public function save(AggregateRoot $object, bool $andFlush = true): void
    {
        $this->checkClass($object);
        $this->getEntityManager()->persist($object);

        if ($andFlush) {
            $this->getEntityManager()->flush($object);
        }
    }

    public function remove(AggregateRoot $object, bool $andFlush = true): void
    {
        $this->checkClass($object);
        $this->getEntityManager()->remove($object);

        if ($andFlush) {
            $this->getEntityManager()->flush($object);
        }
    }

    private function checkClass(AggregateRoot $object): bool
    {
        if (get_class($object) !== $this->entityClass()) {
            throw UnexpectedClassException::create($object, $this->entityClass());
        }

        return true;
    }
}