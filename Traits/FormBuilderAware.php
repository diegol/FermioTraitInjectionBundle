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

use Symfony\Component\Form\FormBuilderInterface;

trait FormBuilderAware
{
    /**
     * @var FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * Returns the form builder.
     *
     * @return FormBuilderInterface The form builder
     */
    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    /**
     * Sets the form builder.
     *
     * @param  FormBuilderInterface $formBuilder The form builder
     * @return void
     */
    public function setFormBuilder(FormBuilderInterface $formBuilder)
    {
        $this->formBuilder = $formBuilder;
    }
}
