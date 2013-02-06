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

use Symfony\Component\HttpFoundation\Request;

trait RequestAware
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Returns the request.
     *
     * @return Request The request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets the request.
     *
     * @param  Request $request The request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
