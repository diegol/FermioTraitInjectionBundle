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

use Symfony\Component\Translation\TranslatorInterface;

trait TranslatorAware
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Returns the translator.
     *
     * @return TranslatorInterface The translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Sets the translator.
     *
     * @param  TranslatorInterface $translator The translator
     * @return void
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}
