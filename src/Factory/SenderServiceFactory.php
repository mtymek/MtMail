<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use MtMail\Service\Sender;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SenderServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Sender
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $transportName = $configuration['mt_mail']['transport'];
        $service = new Sender($serviceLocator->get($transportName));

        if (isset($configuration['mt_mail']['sender_plugins'])
            && is_array($configuration['mt_mail']['sender_plugins'])
        ) {
            $pluginManager = $serviceLocator->get('MtMail\Service\SenderPluginManager');
            foreach (array_unique($configuration['mt_mail']['sender_plugins']) as $plugin) {
                $service->getEventManager()->attachAggregate($pluginManager->get($plugin));
            }
        }

        return $service;
    }
}
