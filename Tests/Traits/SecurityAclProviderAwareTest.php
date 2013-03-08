<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\Tests\DependencyInjection;

use Fermio\Bundle\TraitInjectionBundle\Tests\Implementations\TestSecurityAclProviderAware;

class SecurityAclProviderAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $trait = $this->getObjectForTrait('Fermio\\Bundle\\TraitInjectionBundle\\Traits\\SecurityAclProviderAware');
        $trait->setAclProvider($securityContext = $this->getMock('Symfony\\Component\\Security\\Acl\\Model\\AclProviderInterface'));
        $this->assertSame($securityContext, $trait->getAclProvider());
    }
}
