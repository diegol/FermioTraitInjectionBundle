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

use Symfony\Component\HttpKernel\Log\LoggerInterface;

trait LoggerAware
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Returns the logger.
     *
     * @return LoggerInterface The logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Sets the logger.
     *
     * @param  LoggerInterface $logger (optional) The logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }
}
