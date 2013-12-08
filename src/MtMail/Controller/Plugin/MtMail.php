<?php

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