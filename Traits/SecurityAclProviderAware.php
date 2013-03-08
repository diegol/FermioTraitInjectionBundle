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

use Symfony\Component\Security\Acl\Model\AclProviderInterface;

trait SecurityAclProviderAware
{
    /**
     * @var AclProviderInterface
     */
    protected $aclProvider;

    /**
     * Returns the acl provider.
     *
     * @return AclProviderInterface The acl provider
     */
    public function getAclProvider()
    {
        return $this->aclProvider;
    }

    /**
     * Sets the acl provider.
     *
     * @param  AclProviderInterface $securityContext The acl provider
     * @return void
     */
    public function setAclProvider(AclProviderInterface $aclProvider)
    {
        $this->aclProvider = $aclProvider;
    }
}
