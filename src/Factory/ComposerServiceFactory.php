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
use MtMail\Renderer\RendererInterface;
use MtMail\Service\Composer;
use MtMail\Service\ComposerPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class ComposerServiceFactory
{
    /**
     * Create service
     *
     * @param ContainerInterface|ServiceLocatorInterface $serviceLocator
     * @param string $requestedName
     * @param array $options
     * @return Composer
     */
    public function __invoke(ContainerInterface $serviceLocator)
    {
        $configuration = $serviceLocator->get('Configuration');
        /** @var RendererInterface $renderer */
        $renderer = $serviceLocator->get($configuration['mt_mail']['renderer']);
        $service = new Composer($renderer);

        $pluginManager = $serviceLocator->get(ComposerPluginManager::class);

        if (isset($configuration['mt_mail']['composer_plugins'])
            && is_array($configuration['mt_mail']['composer_plugins'])
        ) {
            foreach (array_unique($configuration['mt_mail']['composer_plugins']) as $plugin) {
                $pluginManager->get($plugin)->attach($service->getEventManager());
            }
        }

        return $service;
    }
}
