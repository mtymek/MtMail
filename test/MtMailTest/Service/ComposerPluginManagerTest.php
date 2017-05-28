<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\ComposerPlugin\PluginInterface;
use MtMail\Exception\RuntimeException;
use MtMail\Service\ComposerPluginManager;
use PHPUnit\Framework\TestCase;
use stdClass;
use Zend\ServiceManager\ServiceManager;

class ComposerPluginManagerTest extends TestCase
{
    /**
     * @var ComposerPluginManager
     */
    protected $pluginManager;

    public function setUp()
    {
        $this->pluginManager = new ComposerPluginManager(new ServiceManager());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testValidatePluginThrowsExceptionIfPluginIsInvalid()
    {
        $this->pluginManager->validatePlugin(new stdClass());
        $this->assertTrue(true);
    }

    public function testValidatePluginDoesNothingIfPluginIsValid()
    {
        $mock = $this->prophesize(PluginInterface::class);
        $this->pluginManager->validatePlugin($mock->reveal());
        $this->assertTrue(true);
    }
}
