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

use Doctrine\Common\Proxy\Exception\UnexpectedValueException;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Nurschool\Common\Domain\AggregateRoot;
use Nurschool\Common\Domain\Model\Repository;
use Nurschool\Common\Infrastructure\Persistence\Exception\UnexpectedClassException;

abstract class DoctrineRepository implements Repository
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

    public function save(AggregateRoot $entity, bool $andFlush = true): void
    {
        $this->checkClass($entity);
        $this->getEntityManager()->persist($entity);

        if ($andFlush) {
            $this->getEntityManager()->flush($entity);
        }
    }

    public function remove(AggregateRoot $entity, bool $andFlush = true): void
    {
        $this->checkClass($entity);
        $this->getEntityManager()->remove($entity);

        if ($andFlush) {
            $this->getEntityManager()->flush($entity);
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

    private function checkClass(AggregateRoot $entity): bool
    {
        if (get_class($entity) !== $this->entityClass()) {
            throw UnexpectedClassException::create($entity, $this->entityClass());
        }

        return true;
    }
}