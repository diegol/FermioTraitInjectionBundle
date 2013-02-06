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

use \Swift_Mailer;

trait SwiftmailerAware
{
    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * Returns the swiftmailer.
     *
     * @return Swift_Mailer The swiftmailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    /**
     * Sets the swiftmailer.
     *
     * @param  Swift_Mailer $mailer The swiftmailer
     * @return void
     */
    public function setMailer(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }
}
