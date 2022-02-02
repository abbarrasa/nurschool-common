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

namespace Nurschool\Common\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Nurschool\Common\Domain\AggregateRoot;
use Nurschool\Common\Infrastructure\Persistence\Exception\UnexpectedClassException;

abstract class DoctrineRepository
{
    private ManagerRegistry $managerRegistry;
    protected Connection $connection;
    protected ObjectRepository $objectRepository;

    public function __construct(ManagerRegistry $managerRegistry, Connection $connection)
    {
        $this->managerRegistry = $managerRegistry;
        $this->connection = $connection;
        $this->objectRepository = $this->getEntityManager()->getRepository($this->entityClass());
    }

    abstract public function entityClass(): string;

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

    /**
     * Get entity manager
     *
     * @return ObjectManager|EntityManager
     */
    private function getEntityManager()
    {
        return $this->managerRegistry->getManager();        
    }

    private function checkClass(AggregateRoot $object): bool
    {
        if (get_class($object) !== $this->entityClass()) {
            throw UnexpectedClassException::create($object, $this->entityClass());
        }

        return true;
    }
}