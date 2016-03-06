<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use MtMail\Factory\LayoutPluginFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class LayoutPluginFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $locator = $this->getMock('MtMail\ComposerPlugin\Manager', ['get']);
        $locator->expects($this->once())->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    [
                        'mt_mail' => [
                            'layout' => 'mail/layout.phtml',
                        ],
                    ]
                )
            );
        $pluginManager = $this->getMock(ServiceLocatorInterface::class, ['get', 'has', 'getServiceLocator']);
        $pluginManager->expects($this->once())->method('getServiceLocator')->will($this->returnValue($locator));

        $factory = new LayoutPluginFactory();
        $service = $factory->createService($pluginManager);
        $this->assertInstanceOf('MtMail\ComposerPlugin\Layout', $service);
        $this->assertEquals('mail/layout.phtml', $service->getLayoutTemplate());
    }
}
