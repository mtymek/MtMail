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
use MtMail\ComposerPlugin\MessageEncoding;

class MessageEncodingPluginFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        if (!method_exists($serviceLocator, 'configure')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $config = $serviceLocator->get('Configuration');
        $plugin = new MessageEncoding();
        if (isset($config['mt_mail']['message_encoding'])) {
            $plugin->setEncoding($config['mt_mail']['message_encoding']);
        }

        return $plugin;
    }
}
