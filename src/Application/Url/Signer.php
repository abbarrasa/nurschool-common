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

namespace Nurschool\Common\Application\Url;

interface Signer
{
    /**
     * Signs a URL.
     */    
    public function sign(string $url): string;

    /**
     * Checks that a URL contains the correct hash.
     */
    public function check(string $url): bool;    
}