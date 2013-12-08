<?php

namespace MtMail\Factory;


use MtMail\Controller\Plugin\MtMail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MtMailPlugin implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MtMail
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = new MtMail(
            $serviceLocator->getServiceLocator()->get('MtMail\Service\Mail')
        );
        return $service;
    }
}