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

namespace Nurschool\Common\Application\Command\Exception;

use Nurschool\Common\Application\Command\Command;

final class CommandNotRegistered extends \RuntimeException
{
    public function __construct(Command $command)
    {
        $commandClass = get_class($command);

        parent::__construct("The command <$commandClass> hasn't a command handler associated");
    }
}