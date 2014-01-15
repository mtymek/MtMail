<?php

namespace MtMail\Factory;

use MtMail\ComposerPlugin\DefaultHeaders;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DefaultHeadersPluginFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
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
