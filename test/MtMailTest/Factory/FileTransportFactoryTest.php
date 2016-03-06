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
use MtMail\Factory\FileTransportFactory;
use Zend\Mail\Transport\File;

class FileTransportFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $locator = $this->getMock(ContainerInterface::class, ['get', 'has']);
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
        $service = $factory($locator);
        $this->assertInstanceOf(File::class, $service);

        // File transport does not provide getOptions method
//        $this->assertEquals(__DIR__, $service->getOptions()->getPath());
    }
}
