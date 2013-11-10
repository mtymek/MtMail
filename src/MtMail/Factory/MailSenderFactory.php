<?php

namespace MtMail\Factory;


use MtMail\Service\MailComposer;
use MtMail\Service\MailSender;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailSenderFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MailComposer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $transportName = $configuration['mt_mail']['transport'];
        $service = new MailSender($serviceLocator->get($transportName));
        return $service;
    }
}