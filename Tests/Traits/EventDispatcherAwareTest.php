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

use Fermio\Bundle\TraitInjectionBundle\Tests\Implementations\TestEventDispatcherAware;

class EventDispatcherAwareTest extends \PHPUnit_Framework_TestCase
{
    public function testTrait()
    {
        $trait = new TestEventDispatcherAware();
        $trait->setEventDispatcher($dispatcher = $this->getMock('Symfony\\Component\\EventDispatcher\\EventDispatcherInterface'));
        $this->assertSame($dispatcher, $trait->getEventDispatcher());
    }
}
