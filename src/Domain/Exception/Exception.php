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

namespace Nurschool\Common\Domain\Exception;

class Exception extends \Exception
{
    protected string $codification;

    /**
     * Get the value of codification
     */ 
    public function getCodification(): string
    {
        return $this->codification;
    }

    /**
     * Set the value of codification
     *
     * @return  self
     */ 
    public function setCodification($codification): self
    {
        $this->codification = $codification;

        return $this;
    }
}