<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use Interop\Container\ContainerInterface;
use MtMail\Service\SenderPluginManager;
use Zend\ServiceManager\Config;

class SenderPluginManagerFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $serviceConfig = new Config(
            isset($config['mt_mail']['sender_plugin_manager'])?$config['mt_mail']['sender_plugin_manager']:[]
        );
        $service = new SenderPluginManager($serviceLocator);
        $serviceConfig->configureServiceManager($service);

        return $service;
    }
}
