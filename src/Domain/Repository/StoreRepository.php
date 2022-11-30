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

namespace Nurschool\Common\Domain\Repository;

use Nurschool\Common\Domain\AggregateRoot;

interface StoreRepository
{
    public function save(AggregateRoot $object, bool $andFlush = true): void;

    public function remove(AggregateRoot $object, bool $andFlush = true): void;
}