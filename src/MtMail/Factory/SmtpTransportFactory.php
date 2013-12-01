<?php

namespace MtMail\Factory;

use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SmtpTransportFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Smtp
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $serviceConfig = isset($configuration['mt_mail']['transport_options'])
            ? $configuration['mt_mail']['transport_options'] : array();
        $options = new SmtpOptions($serviceConfig);
        $smtp = new Smtp($options);
        return $smtp;
    }
}