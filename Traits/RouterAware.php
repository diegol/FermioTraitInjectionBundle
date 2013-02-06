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

use Symfony\Component\Routing\RouterInterface;

trait RouterAware
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Returns the router.
     *
     * @return RouterInterface The router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Sets the router.
     *
     * @param  RouterInterface $router The router
     * @return void
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }
}
