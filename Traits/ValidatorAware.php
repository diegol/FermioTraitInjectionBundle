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

use Symfony\Component\Validator\ValidatorInterface;

trait ValidatorAware
{
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Returns the validator.
     *
     * @return ValidatorInterface The validator
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * Sets the validator.
     *
     * @param  ValidatorInterface $validator The validator
     * @return void
     */
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
}
