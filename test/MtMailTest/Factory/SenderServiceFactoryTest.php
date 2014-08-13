<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use MtMail\Factory\SenderServiceFactory;

class SenderServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));

        $serviceLocator->expects($this->at(0))
            ->method('get')
            ->with('Configuration')
            ->will($this->returnValue(array('mt_mail' => array('sender_plugins' => array('DefaultHeaders', 'DefaultHeaders'), 'transport' => 'transport.file'))));

        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');
        $serviceLocator->expects($this->at(1))
            ->method('get')
            ->with('transport.file')
            ->will($this->returnValue($transport));

        $pluginManager = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));

        $serviceLocator->expects($this->at(2))
            ->method('get')
            ->with('MtMail\Service\SenderPluginManager')
            ->will($this->returnValue($pluginManager));

        $plugin = $this->getMock('MtMail\SenderPlugin\PluginInterface');

        $pluginManager->expects($this->once())
            ->method('get')
            ->with('DefaultHeaders')
            ->will($this->returnValue($plugin));

        $factory = new SenderServiceFactory;

        $this->assertInstanceOf('MtMail\Service\Sender', $factory->createService($serviceLocator));
    }
}
