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
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;

class FileTransportFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $serviceConfig = isset($configuration['mt_mail']['transport_options'])
            ? $configuration['mt_mail']['transport_options'] : [];
        $options = new FileOptions($serviceConfig);
        $file = new File($options);

        return $file;
    }
}
