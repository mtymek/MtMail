<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Service;

use MtMail\ComposerPlugin\PluginInterface;
use MtMail\Exception\RuntimeException;
use Zend\ServiceManager\Exception;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class ComposerPluginManager extends ServiceManager implements ServiceLocatorAwareInterface
{

    /**
     * The main service locator
     *
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Set the main service locator so factories can have access to it to pull deps
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return self
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        return $this;
    }

    /**
     * Get the main plugin manager. Useful for fetching dependencies from within factories.
     *
     * @return mixed
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Validate the plugin
     *
     *
     * @param  mixed            $plugin
     * @throws RuntimeException
     * @return void
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof PluginInterface) {
            throw new RuntimeException(sprintf(
                'Plugin of type %s is invalid; must implement %s\FilterInterface or be callable',
                (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
                __NAMESPACE__
            ));
        }
    }

    /**
     * @param  string            $name
     * @param  bool              $usePeeringServiceManagers
     * @return array|object|void
     */
    public function get($name, $usePeeringServiceManagers = true)
    {
        $instance = parent::get($name, $usePeeringServiceManagers);
        $this->validatePlugin($instance);

        return $instance;
    }
}
