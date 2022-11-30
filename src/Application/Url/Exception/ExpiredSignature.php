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

namespace Nurschool\Common\Application\Url\Exception;

use Exception;

final class ExpiredSignature extends Exception
{
    public static function create(): self
    {
        return new self('The link has expired. Please request a new link.');
    }
}