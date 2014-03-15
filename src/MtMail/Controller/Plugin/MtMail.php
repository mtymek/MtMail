<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Controller\Plugin;

use MtMail\Service\Mail;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class MtMail extends AbstractPlugin
{
    /**
     * @var Mail
     */
    protected $mailService;

    public function __construct(Mail $mailService)
    {
        $this->mailService = $mailService;
    }

    public function __invoke()
    {
        return $this->mailService;
    }
}
