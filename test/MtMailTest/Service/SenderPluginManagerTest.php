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
use MtMail\Service\SenderPluginManager;
use PHPUnit_Framework_TestCase;
use stdClass;

class SenderPluginManagerTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var SenderPluginManager
     */
    protected $pluginManager;

    public function setUp()
    {
        $this->pluginManager = new SenderPluginManager();
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
        $mock = $this->getMock('MtMail\SenderPlugin\PluginInterface');
        $this->pluginManager->validatePlugin($mock);
    }
}
