<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Template;

use MtMail\Exception\RuntimeException;
use MtMail\Service\TemplateManager;
use MtMail\Template\TemplateInterface;
use Zend\ServiceManager\ServiceManager;

class ManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var TemplateManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new TemplateManager(new ServiceManager());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testValidatePluginThrowsExceptionWhenClassIsInvalid()
    {
        $this->manager->validatePlugin(new \stdClass());
    }

    public function testValidatePluginDoesNothingIfPluginIsValid()
    {
        $mock = $this->prophesize(TemplateInterface::class);
        $this->manager->validatePlugin($mock->reveal());
        $this->assertTrue(true);
    }

    /**
     * @expectedException RuntimeException
     */
    public function testValidateThrowsExceptionWhenClassIsInvalid()
    {
        $this->manager->validate(new \stdClass());
    }

    public function testValidateDoesNothingIfPluginIsValid()
    {
        $mock = $this->prophesize(TemplateInterface::class);
        $this->manager->validate($mock->reveal());
        $this->assertTrue(true);
    }
}
