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

use Symfony\Component\HttpKernel\HttpKernelInterface;

trait KernelAware
{
    /**
     * @var HttpKernelInterface
     */
    protected $kernel;

    /**
     * Returns the kernel.
     *
     * @return HttpKernelInterface The kernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }

    /**
     * Sets the kernel.
     *
     * @param  HttpKernelInterface $kernel The kernel
     * @return void
     */
    public function setKernel(HttpKernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
