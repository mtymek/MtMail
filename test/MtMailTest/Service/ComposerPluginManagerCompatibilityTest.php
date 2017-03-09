<?php

namespace MtMailTest\Service;

use MtMail\ComposerPlugin\PluginInterface;
use MtMail\Exception\RuntimeException;
use MtMail\Service\ComposerPluginManager;
use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\Test\CommonPluginManagerTrait;

class ComposerPluginManagerCompatibilityTest extends TestCase
{
    use CommonPluginManagerTrait;

    protected function getPluginManager()
    {
        $parent = new ServiceManager();
        $manager = new ComposerPluginManager($parent);
        $configArray = include __DIR__ . '/../../../config/module.config.php';
        $config = new Config($configArray['mt_mail']['composer_plugin_manager']);
        $config->configureServiceManager($manager);
        $parent->setService('Configuration', $configArray);
        return $manager;
    }

    protected function getV2InvalidPluginException()
    {
        return RuntimeException::class;
    }

    protected function getInstanceOf()
    {
        return PluginInterface::class;
    }
}
