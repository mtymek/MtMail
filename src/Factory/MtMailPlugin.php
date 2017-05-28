<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Factory;

use Interop\Container\ContainerInterface;
use MtMail\Controller\Plugin\MtMail;
use MtMail\Service\Mail;

class MtMailPlugin
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        if (!method_exists($serviceLocator, 'configure')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $service = new MtMail(
            $serviceLocator->get(Mail::class)
        );

        return $service;
    }
}
