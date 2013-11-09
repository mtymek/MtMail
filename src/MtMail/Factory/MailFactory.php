<?php

namespace MtMail\Factory;


use MtMail\Service\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Mail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $rendererName = $configuration['mt_mail']['renderer'];
        $transportName = $configuration['mt_mail']['transport'];
        $service = new Mail($serviceLocator->get($rendererName), $serviceLocator->get($transportName));
        return $service;
    }
}