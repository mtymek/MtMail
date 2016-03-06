<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use MtMail\Factory\FileTransportFactory;

class FileTransportFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $locator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', ['get', 'has']);
        $locator->expects($this->once())->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    [
                        'mt_mail' => [
                            'transport_options' => [
                                'path' => __DIR__, // directory must exist
                            ]
                        ],
                    ]
                )
            );
        $factory = new FileTransportFactory();
        $service = $factory->createService($locator);
        $this->assertInstanceOf('Zend\Mail\Transport\File', $service);

        // File transport does not provide getOptions method
//        $this->assertEquals(__DIR__, $service->getOptions()->getPath());
    }
}
