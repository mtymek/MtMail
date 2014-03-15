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
use MtMail\Service\ComposerPluginManager;
use PHPUnit_Framework_TestCase;
use stdClass;

class ComposerPluginManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var ComposerPluginManager
     */
    protected $pluginManager;

    public function setUp()
    {
        $this->pluginManager = new ComposerPluginManager();
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
        $mock = $this->getMock('MtMail\ComposerPlugin\PluginInterface');
        $this->pluginManager->validatePlugin($mock);
    }
}
