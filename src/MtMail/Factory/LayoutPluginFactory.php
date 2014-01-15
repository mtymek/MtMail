<?php

namespace MtMail\Factory;

use MtMail\ComposerPlugin\Layout;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LayoutPluginFactory implements FactoryInterface
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
        $plugin = new Layout();
        if (isset($config['mt_mail']['layout'])) {
            $plugin->setLayoutTemplate($config['mt_mail']['layout']);
        }
        return $plugin;
    }
}
