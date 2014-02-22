<?php

namespace MtMail\Factory;

use MtMail\Template\Manager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TemplateManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $serviceConfig = new Config(isset($config['mt_mail']['template_manager'])?$config['mt_mail']['template_manager']:array());
        $service = new Manager($serviceConfig);
        $service->setServiceLocator($serviceLocator);
        return $service;
    }
}
