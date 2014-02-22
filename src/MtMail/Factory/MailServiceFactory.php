<?php

namespace MtMail\Factory;

use MtMail\Service\Mail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Mail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new Mail(
            $serviceLocator->get('MtMail\Service\Composer'),
            $serviceLocator->get('MtMail\Service\Sender'),
            $serviceLocator->get('MtMail\Template\Manager')
        );
        return $service;
    }
}
