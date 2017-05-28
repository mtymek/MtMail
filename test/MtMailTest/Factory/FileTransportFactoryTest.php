<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use Interop\Container\ContainerInterface;
use MtMail\Factory\FileTransportFactory;
use Zend\Mail\Transport\File;

class FileTransportFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateService()
    {
        $locator = $this->prophesize(ContainerInterface::class);
        $locator->get('Configuration')->willReturn(
            [
                'mt_mail' => [
                    'transport_options' => [
                        'path' => __DIR__, // directory must exist
                    ]
                ],
            ]
        );
        $factory = new FileTransportFactory();
        $service = $factory($locator->reveal());
        $this->assertInstanceOf(File::class, $service);
    }
}
