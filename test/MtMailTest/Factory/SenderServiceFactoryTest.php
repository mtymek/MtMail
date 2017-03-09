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
use MtMail\SenderPlugin\PluginInterface;
use MtMail\Service\Sender;
use MtMail\Service\SenderPluginManager;
use Zend\Mail\Transport\TransportInterface;

class SenderServiceFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateService()
    {
        $serviceLocator = $this->prophesize(ContainerInterface::class);

        $serviceLocator->get('Configuration')
            ->willReturn(
                [
                    'mt_mail' => [
                        'sender_plugins' => ['DefaultHeaders', 'DefaultHeaders'], 'transport' => 'transport.file'
                    ]
                ]
            );

        $transport = $this->prophesize(TransportInterface::class);
        $serviceLocator->get('transport.file')
            ->willReturn($transport);

        $pluginManager = $this->prophesize(ContainerInterface::class);

        $serviceLocator->get(SenderPluginManager::class)
            ->willReturn($pluginManager->reveal());

        $plugin = $this->prophesize(PluginInterface::class);

        $pluginManager->get('DefaultHeaders')
            ->willReturn($plugin->reveal());

        $factory = new SenderServiceFactory;

        $this->assertInstanceOf(Sender::class, $factory($serviceLocator->reveal()));
    }
}
