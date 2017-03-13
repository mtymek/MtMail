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
use MtMail\Service\Composer;
use MtMail\Service\Mail;
use MtMail\Service\Sender;
use MtMail\Service\TemplateManager;

class MailServiceFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $service = new Mail(
            $serviceLocator->get(Composer::class),
            $serviceLocator->get(Sender::class),
            $serviceLocator->get(TemplateManager::class)
        );

        return $service;
    }
}
