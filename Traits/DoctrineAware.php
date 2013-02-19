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

use Doctrine\Common\Persistence\ManagerRegistry;

trait DoctrineAware
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * Returns the Doctrine registry manager.
     *
     * @return ManagerRegistry The Doctrine registry manager
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * Sets the Doctrine registry manager.
     *
     * @param  ManagerRegistry $doctrine The Doctrine registry manager
     * @return void
     */
    public function setDoctrine(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
}
