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

use Symfony\Component\HttpKernel\KernelInterface;

trait KernelAware
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Returns the kernel.
     *
     * @return KernelInterface The kernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Sets the kernel.
     *
     * @param  KernelInterface $kernel The kernel
     * @return void
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
