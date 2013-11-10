<?php

namespace MtMail\Factory;


use MtMail\Service\MailComposer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailComposerFactory implements FactoryInterface
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
        $transportName = $configuration['mt_mail']['renderer'];
        $service = new MailComposer($serviceLocator->get($transportName));
        return $service;
    }
}