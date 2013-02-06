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

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAware
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * Returns the event dispatcher.
     *
     * @return EventDispatcherInterface The event dispatcher
     */
    public function getEventDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * Sets the event dispatcher.
     *
     * @param  EventDispatcherInterface $dispatcher The event dispatcher
     * @return void
     */
    public function setEventDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }
}
