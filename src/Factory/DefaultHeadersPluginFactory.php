<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use MtMail\ComposerPlugin\DefaultHeaders;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultHeadersPluginFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('Configuration');
        $plugin = new DefaultHeaders();
        if (isset($config['mt_mail']['default_headers'])) {
            $plugin->setHeaders($config['mt_mail']['default_headers']);
        }

        return $plugin;
    }
}
