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
use MtMail\ComposerPlugin\DefaultHeaders;

class DefaultHeadersPluginFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        if (!method_exists($serviceLocator, 'configure')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $config = $serviceLocator->get('Configuration');
        $plugin = new DefaultHeaders();
        if (isset($config['mt_mail']['default_headers'])) {
            $plugin->setHeaders($config['mt_mail']['default_headers']);
        }

        return $plugin;
    }
}
