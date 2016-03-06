<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use Interop\Container\ContainerInterface;
use MtMail\Factory\SenderServiceFactory;
use MtMail\Service\Sender;

class SenderServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->getMock(ContainerInterface::class, ['get', 'has']);

        $serviceLocator->expects($this->at(0))
            ->method('get')
            ->with('Configuration')
            ->will(
                $this->returnValue(
                    [
                        'mt_mail' => [
                            'sender_plugins' => ['DefaultHeaders', 'DefaultHeaders'], 'transport' => 'transport.file'
                        ]
                    ]
                )
            );

        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');
        $serviceLocator->expects($this->at(1))
            ->method('get')
            ->with('transport.file')
            ->will($this->returnValue($transport));

        $pluginManager = $this->getMock(ContainerInterface::class, ['get', 'has']);

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

        $this->assertInstanceOf(Sender::class, $factory($serviceLocator));
    }
}
