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
use MtMail\ComposerPlugin\Layout;

class LayoutPluginFactory
{
    public function __invoke(ContainerInterface $serviceLocator)
    {
        if (!method_exists($serviceLocator, 'configure')) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }
        $config = $serviceLocator->get('Configuration');
        $plugin = new Layout();
        if (isset($config['mt_mail']['layout'])) {
            $plugin->setLayoutTemplate($config['mt_mail']['layout']);
        }

        return $plugin;
    }
}
