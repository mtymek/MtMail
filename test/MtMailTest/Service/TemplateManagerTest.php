<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Template;

use MtMail\Exception\RuntimeException;
use MtMail\Service\TemplateManager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TemplateManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new TemplateManager();
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
        $mock = $this->getMock('MtMail\Template\TemplateInterface');
        $this->manager->validatePlugin($mock);
    }
}
