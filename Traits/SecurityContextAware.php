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

use Symfony\Component\Security\Core\SecurityContextInterface;

trait SecurityContextAware
{
    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * Returns the security context.
     *
     * @return SecurityContextInterface The security context
     */
    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    /**
     * Sets the security context.
     *
     * @param  SecurityContextInterface $securityContext The security context
     * @return void
     */
    public function setSecurityContext(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }
}
