<?php

namespace Nurschool\Common\Domain\Model;

use Nurschool\Common\Domain\AggregateRoot;

interface Repository
{
    public function save(AggregateRoot $object, bool $andFlush = true): void;

    public function remove(AggregateRoot $object, bool $andFlush = true): void;
}