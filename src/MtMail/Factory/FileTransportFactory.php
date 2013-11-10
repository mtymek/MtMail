<?php

namespace MtMail\Factory;

use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FileTransportFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return File
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        $serviceConfig = isset($configuration['mt_mail']['file_options']) ? $configuration['mt_mail']['file_options'] : array();
        $options = new FileOptions($serviceConfig);
        $file = new File($options);
        return $file;
    }
}