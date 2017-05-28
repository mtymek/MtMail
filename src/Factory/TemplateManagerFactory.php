<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use Interop\Container\ContainerInterface;
use MtMail\Service\TemplateManager;
use Zend\ServiceManager\Config;

class TemplateManagerFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');
        $serviceConfig = new Config(
            isset($config['mt_mail']['template_manager']) ? $config['mt_mail']['template_manager']:[]
        );
        $service = new TemplateManager($serviceLocator);
        $serviceConfig->configureServiceManager($service);

        return $service;
    }
}
