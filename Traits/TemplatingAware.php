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

use Symfony\Component\Templating\EngineInterface;

trait TemplatingAware
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * Returns the templating engine.
     *
     * @return EngineInterface The templating engine
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * Sets the templating engine.
     *
     * @param  EngineInterface $templating The templating engine
     * @return void
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }
}
