<?php

namespace MtMailTest\Template;


use MtMail\Exception\RuntimeException;
use MtMail\Template\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Manager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new Manager();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testValidatePluginThrowsExceptionWhenClassIsInvalid()
    {
        $this->manager->validatePlugin(new \stdClass());
    }

}
