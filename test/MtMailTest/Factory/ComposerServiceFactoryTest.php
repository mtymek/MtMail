<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use Interop\Container\ContainerInterface;
use MtMail\ComposerPlugin\PluginInterface;
use MtMail\Factory\ComposerServiceFactory;
use MtMail\Renderer\RendererInterface;
use MtMail\Service\Composer;
use MtMail\Service\ComposerPluginManager;

class ComposerServiceFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateService()
    {
        $pluginManager = $this->prophesize(ComposerPluginManager::class);
        $locator = $this->prophesize(ContainerInterface::class);
        $locator->get('Configuration')->willReturn(
            [
                'mt_mail' => [
                    'renderer' => 'Some\Mail\Renderer',
                ],
            ]
        );

        $renderer = $this->prophesize(RendererInterface::class);
        $locator->get('Some\Mail\Renderer')->willReturn($renderer->reveal());
        $locator->get(ComposerPluginManager::class)->willReturn($pluginManager->reveal());

        $factory = new ComposerServiceFactory();
        $service = $factory($locator->reveal());
        $this->assertInstanceOf(Composer::class, $service);
        $this->assertEquals($renderer->reveal(), $service->getRenderer());
    }

    public function testCreateServiceCanInjectPlugins()
    {
        $plugin = $this->prophesize(PluginInterface::class);
        $pluginManager = $this->prophesize(ComposerPluginManager::class);
        $pluginManager->get('SomeMailPlugin')
            ->willReturn($plugin->reveal());

        $locator = $this->prophesize(ContainerInterface::class);
        $locator->get('Configuration')->willReturn(
            [
                'mt_mail' => [
                    'renderer' => 'Some\Mail\Renderer',
                    'composer_plugins' => [
                        'SomeMailPlugin',
                        'SomeMailPlugin',
                    ],
                ],
            ]
        );
        $renderer = $this->prophesize(RendererInterface::class);
        $locator->get('Some\Mail\Renderer')->willReturn($renderer->reveal());
        $locator->get(ComposerPluginManager::class)->willReturn($pluginManager->reveal());

        $factory = new ComposerServiceFactory();
        $service = $factory($locator->reveal());
        $this->assertInstanceOf(Composer::class, $service);
        $this->assertEquals($renderer->reveal(), $service->getRenderer());
    }
}
