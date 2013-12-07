<?php

namespace MtMail\Factory;


use MtMail\Service\Composer;
use MtMail\Service\Sender;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SenderServiceFactory implements FactoryInterface
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
        $transportName = $configuration['mt_mail']['transport'];
        $service = new Sender($serviceLocator->get($transportName));

        if (is_array($configuration['mt_mail']['plugins'])) {
            $pluginManager = $serviceLocator->get('MtMail\Plugin\Manager');
            foreach ($configuration['mt_mail']['plugins'] as $plugin) {
                $service->getEventManager()->attachAggregate($pluginManager->get($plugin));
            }
        }

        return $service;
    }
}
