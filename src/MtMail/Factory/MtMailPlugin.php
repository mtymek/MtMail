<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use MtMail\Controller\Plugin\MtMail;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MtMailPlugin implements FactoryInterface
{

    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
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
