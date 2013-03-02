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

use Fermio\Bundle\TraitInjectionBundle\Tests\Implementations\TestSwiftmailerAware;

class SwiftmailerAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $mailer = $this
            ->getMockBuilder('Swift_Mailer')
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $trait = $this->getObjectForTrait('Fermio\\Bundle\\TraitInjectionBundle\\Traits\\SwiftmailerAware');
        $trait->setMailer($mailer);
        $this->assertSame($mailer, $trait->getMailer());
    }
}
