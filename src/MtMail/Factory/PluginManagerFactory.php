<?php

namespace MtMail\Factory;

use MtMail\Plugin\Manager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PluginManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $serviceConfig = new Config($config['mt_mail']['plugin_manager']);
        $service = new Manager($serviceConfig);
        $service->setServiceLocator($serviceLocator);
        return $service;
    }
}
