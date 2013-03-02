<?php

/*
 * This file is part of the FermioTraitInjectionBundle package.
 *
 * (c) Pierre Minnieur <pierre@ferm.io>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Fermio\Bundle\TraitInjectionBundle\Tests;

use Fermio\Bundle\TraitInjectionBundle\FermioTraitInjectionBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;

class FermioTraitInjectionBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testBundle()
    {
        $this->assertInstanceOf(
            'Symfony\\Component\\HttpKernel\\Bundle\\Bundle',
            new FermioTraitInjectionBundle()
        );
    }

    public function testBuild()
    {
        $container = $this->getMock('Symfony\\Component\\DependencyInjection\\ContainerBuilder');
        $container
            ->expects($this->once())
            ->method('addCompilerPass')
            ->with(
                $this->isInstanceOf('Fermio\\Bundle\\TraitInjectionBundle\\DependencyInjection\\Compiler\\AddMethodCallsPass'),
                $this->equalTo(PassConfig::TYPE_AFTER_REMOVING)
            )
        ;

        (new FermioTraitInjectionBundle())->build($container);
    }
}
