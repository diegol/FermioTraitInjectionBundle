<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\Traits;

use Symfony\Bridge\Doctrine\RegistryInterface;

trait DoctrineAware
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * Returns the doctrine registry.
     *
     * @return RegistryInterface The doctrine registry
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * Sets the doctrine registry.
     *
     * @param  RegistryInterface $doctrine The doctrine registry
     * @return void
     */
    public function setDoctrine(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }
}
