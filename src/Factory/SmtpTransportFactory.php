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
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;

class SmtpTransportFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $serviceConfig = isset($configuration['mt_mail']['transport_options'])
            ? $configuration['mt_mail']['transport_options'] : [];
        $options = new SmtpOptions($serviceConfig);
        $smtp = new Smtp($options);

        return $smtp;
    }
}
