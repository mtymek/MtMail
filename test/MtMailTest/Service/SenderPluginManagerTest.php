<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Exception\RuntimeException;
use MtMail\SenderPlugin\PluginInterface;
use MtMail\Service\SenderPluginManager;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\ServiceManager\ServiceManager;

class SenderPluginManagerTest extends TestCase
{
    /**
     * @var SenderPluginManager
     */
    protected $pluginManager;

    public function setUp()
    {
        $this->pluginManager = new SenderPluginManager(new ServiceManager());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testValidatePluginThrowsExceptionIfPluginIsInvalid()
    {
        $this->pluginManager->validatePlugin(new stdClass());
    }

    public function testValidatePluginDoesNothingIfPluginIsValid()
    {
        $mock = $this->prophesize(PluginInterface::class);
        $this->pluginManager->validatePlugin($mock->reveal());
        $this->assertTrue(true);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testValidateThrowsExceptionIfPluginIsInvalid()
    {
        $this->pluginManager->validate(new stdClass());
    }

    public function testValidateDoesNothingIfPluginIsValid()
    {
        $mock = $this->prophesize(PluginInterface::class);
        $this->pluginManager->validate($mock->reveal());
        $this->assertTrue(true);
    }
}
