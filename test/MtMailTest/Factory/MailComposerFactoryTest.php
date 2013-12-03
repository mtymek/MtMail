<?php

namespace MtMailTest\Factory;

use MtMail\Factory\LayoutPluginFactory;
use MtMail\Factory\MailComposerFactory;

class MailComposerFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $locator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));
        $locator->expects($this->at(0))->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    array(
                        'mt_mail' => array(
                            'renderer' => 'Some\Mail\Renderer',
                            'plugins' => array(),
                        ),
                    )
                )
            );

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $locator->expects($this->at(1))->method('get')
            ->with('Some\Mail\Renderer')->will(
                $this->returnValue($renderer)
            );

        $factory = new MailComposerFactory();
        $service = $factory->createService($locator);
        $this->assertInstanceOf('MtMail\Service\MailComposer', $service);
        $this->assertEquals($renderer, $service->getRenderer());
    }

    public function testCreateServiceCanInjectPlugins()
    {
        $locator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));
        $locator->expects($this->at(0))->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    array(
                        'mt_mail' => array(
                            'renderer' => 'Some\Mail\Renderer',
                            'plugins' => array(
                                'SomeMailPlugin',
                            ),
                        ),
                    )
                )
            );
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $locator->expects($this->at(1))->method('get')
            ->with('Some\Mail\Renderer')->will(
                $this->returnValue($renderer)
            );

        $plugin = $this->getMock('MtMail\Plugin\PluginInterface');
        $pluginManager = $this->getMock('MtMail\Plugin\Manager', array('get'));
        $pluginManager->expects($this->once())->method('get')->with('SomeMailPlugin')
            ->will($this->returnValue($plugin));
        $locator->expects($this->at(2))->method('get')
            ->with('MtMail\Plugin\Manager')->will(
                $this->returnValue($pluginManager)
            );


        $factory = new MailComposerFactory();
        $service = $factory->createService($locator);
        $this->assertInstanceOf('MtMail\Service\MailComposer', $service);
        $this->assertEquals($renderer, $service->getRenderer());
    }

}