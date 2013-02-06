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

use Fermio\Bundle\TraitInjectionBundle\Tests\Implementations\TestValidatorAware;

class ValidatorAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $trait = new TestValidatorAware();
        $trait->setValidator($validator = $this->getMock('Symfony\\Component\\Validator\\ValidatorInterface'));
        $this->assertSame($validator, $trait->getValidator());
    }
}
