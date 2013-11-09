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
        $options = new SmtpOptions();
        $options->__construct($configuration['mt_mail']['smtp_options']);
        $smtp = new Smtp($options);
        return $smtp;
    }
}