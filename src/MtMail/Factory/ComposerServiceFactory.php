<?php

namespace MtMail\Factory;


use MtMail\Plugin\Layout;
use MtMail\Service\Composer;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ComposerServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Composer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $renderer = $configuration['mt_mail']['renderer'];
        $service = new Composer($serviceLocator->get($renderer));

        if (is_array($configuration['mt_mail']['plugins'])) {
            $pluginManager = $serviceLocator->get('MtMail\Plugin\Manager');
            foreach ($configuration['mt_mail']['plugins'] as $plugin) {
                $service->getEventManager()->attachAggregate($pluginManager->get($plugin));
            }
        }

        return $service;
    }
}
