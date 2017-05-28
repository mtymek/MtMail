<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use MtMail\ComposerPlugin\Layout;
use MtMail\Factory\LayoutPluginFactory;
use MtMail\Service\ComposerPluginManager;
use Zend\ServiceManager\ServiceManager;

class LayoutPluginFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateService()
    {
        $locator = $this->prophesize(ComposerPluginManager::class);
        $locator->get('Configuration')->willReturn(
            [
                'mt_mail' => [
                    'layout' => 'mail/layout.phtml',
                ],
            ]
        );
        $serviceManager = $this->prophesize(ServiceManager::class);
        $locator->getServiceLocator()->willReturn($serviceManager->reveal());

        $factory = new LayoutPluginFactory();
        $service = $factory($locator->reveal());
        $this->assertInstanceOf(Layout::class, $service);
        $this->assertEquals('mail/layout.phtml', $service->getLayoutTemplate());
    }
}
