<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest;

use MtMail\Module;
use PHPUnit\Framework\TestCase;

class ModuleTest extends TestCase
{
    public function testGetConfigReturnsValidConfig()
    {
        $module = new Module();
        $config = $module->getConfig();
        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('mt_mail', $config);
    }

    public function provideServiceList()
    {
        $config = include __DIR__ . '/../../config/module.config.php';
        $serviceConfig = array_merge(
            isset($config['service_manager']['factories'])?$config['service_manager']['factories']:[],
            isset($config['service_manager']['invokables'])?$config['service_manager']['invokables']:[]
        );
        $services = [];
        foreach ($serviceConfig as $key => $val) {
            $services[] = [$key];
        }
        return $services;
    }

    /**
     * @dataProvider provideServiceList
     */
    public function testService($service)
    {
        $sm = Bootstrap::getServiceManager();
        $this->assertTrue($sm->has($service));
        $this->assertInstanceOf($service, $sm->get($service));
    }

    public function provideControllerPluginList()
    {
        $config = include __DIR__ . '/../../config/module.config.php';
        $serviceConfig = array_merge(
            isset($config['controller_plugins']['factories'])?$config['controller_plugins']['factories']:[],
            isset($config['controller_plugins']['invokables'])?$config['controller_plugins']['invokables']:[]
        );
        $services = [];
        foreach ($serviceConfig as $key => $val) {
            $services[] = [$key];
        }
        return $services;
    }

    /**
     * @dataProvider provideControllerPluginList
     */
    public function testControllerPlugin($plugin)
    {
        $sm = Bootstrap::getServiceManager()->get('ControllerPluginManager');
        $this->assertTrue($sm->has($plugin));
        $this->assertInstanceOf('MtMail\Controller\Plugin\\' . $plugin, $sm->get($plugin));
    }
}
