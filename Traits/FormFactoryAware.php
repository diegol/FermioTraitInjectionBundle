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

use Symfony\Component\Form\FormFactoryInterface;

trait FormFactoryAware
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Returns the form factory.
     *
     * @return FormFactoryInterface The form factory
     */
    public function getFormFactory()
    {
        return $this->formFactory;
    }

    /**
     * Sets the form factory.
     *
     * @param  FormFactoryInterface $formFactory The form factory
     * @return void
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
}
