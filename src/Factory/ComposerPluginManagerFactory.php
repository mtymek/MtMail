<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use Interop\Container\ContainerInterface;
use MtMail\Service\ComposerPluginManager;
use Zend\ServiceManager\Config;

class ComposerPluginManagerFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $serviceConfig = new Config($config['mt_mail']['composer_plugin_manager']);
        $service = new ComposerPluginManager($serviceLocator);
        $serviceConfig->configureServiceManager($service);

        return $service;
    }
}
