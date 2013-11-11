<?php

namespace MtMail\Factory;


use MtMail\Service\MailComposer;
use MtMail\Service\MailSender;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailSenderFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MailComposer
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $transportName = $configuration['mt_mail']['transport'];
        $service = new MailSender($serviceLocator->get($transportName));

        if (is_array($configuration['mt_mail']['plugins'])) {
            $pluginManager = $serviceLocator->get('MtMail\Plugin\Manager');
            foreach ($configuration['mt_mail']['plugins'] as $plugin) {
                $service->getEventManager()->attachAggregate($pluginManager->get($plugin));
            }
        }

        return $service;
    }
}