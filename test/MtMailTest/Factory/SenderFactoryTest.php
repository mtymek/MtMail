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

class SenderFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $locator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));
        $locator->expects($this->at(0))->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    array(
                        'mt_mail' => array(
                            'transport' => 'Some\Mail\Transport',
                        ),
                    )
                )
            );

        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');
        $locator->expects($this->at(1))->method('get')
            ->with('Some\Mail\Transport')->will(
                $this->returnValue($transport)
            );

        $factory = new SenderServiceFactory();
        $service = $factory->createService($locator);
        $this->assertInstanceOf('MtMail\Service\Sender', $service);
    }

    public function testCreateServiceCanInjectPlugins()
    {
        $locator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));
        $locator->expects($this->at(0))->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    array(
                        'mt_mail' => array(
                            'transport' => 'Some\Mail\Transport',
                            'sender_plugins' => array(
                                'SomeSenderPlugin',
                            ),
                        ),
                    )
                )
            );

        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');
        $locator->expects($this->at(1))->method('get')
            ->with('Some\Mail\Transport')->will(
                $this->returnValue($transport)
            );

        $plugin = $this->getMock('MtMail\SenderPlugin\PluginInterface');
        $pluginManager = $this->getMock('MtMail\Service\SenderPluginManager', array('get'));
        $pluginManager->expects($this->once())->method('get')->with('somesenderplugin')
            ->will($this->returnValue($plugin));
        $locator->expects($this->at(2))->method('get')
            ->with('MtMail\Service\SenderPluginManager')->will(
                $this->returnValue($pluginManager)
            );

        $factory = new SenderServiceFactory();
        $service = $factory->createService($locator);
        $this->assertInstanceOf('MtMail\Service\Sender', $service);
    }

}
