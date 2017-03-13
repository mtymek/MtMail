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
use MtMail\Service\Sender;
use MtMail\Service\SenderPluginManager;

class SenderServiceFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $transportName = $configuration['mt_mail']['transport'];
        $service = new Sender($serviceLocator->get($transportName));

        if (isset($configuration['mt_mail']['sender_plugins'])
            && is_array($configuration['mt_mail']['sender_plugins'])
        ) {
            $pluginManager = $serviceLocator->get(SenderPluginManager::class);
            foreach (array_unique($configuration['mt_mail']['sender_plugins']) as $plugin) {
                $pluginManager->get($plugin)->attach($service->getEventManager());
            }
        }

        return $service;
    }
}
