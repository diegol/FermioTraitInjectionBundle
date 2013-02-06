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

use Fermio\Bundle\TraitInjectionBundle\Tests\Implementations\TestFormBuilderAware;

class FormBuilderAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $formBuilder = $this
            ->getMockBuilder('Symfony\\Component\\Form\\FormBuilder')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $trait = new TestFormBuilderAware();
        $trait->setFormBuilder($formBuilder);
        $this->assertSame($formBuilder, $trait->getFormBuilder());
    }
}
