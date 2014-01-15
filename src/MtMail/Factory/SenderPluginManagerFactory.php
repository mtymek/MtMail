<?php

namespace MtMail\Factory;

use MtMail\SenderPlugin\Manager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SenderPluginManagerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $serviceConfig = new Config(
            isset($config['mt_mail']['sender_plugin_manager'])?$config['mt_mail']['sender_plugin_manager']:array()
        );
        $service = new Manager($serviceConfig);
        $service->setServiceLocator($serviceLocator);
        return $service;
    }
}
