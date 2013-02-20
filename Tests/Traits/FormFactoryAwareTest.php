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

use Fermio\Bundle\TraitInjectionBundle\Tests\Implementations\TestFormFactoryAware;

class FormFactoryAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $formFactory = $this->getMock('Symfony\\Component\\Form\\FormFactoryInterface');

        $trait = new TestFormFactoryAware();
        $trait->setFormFactory($formFactory);
        $this->assertSame($formFactory, $trait->getFormFactory());
    }
}
