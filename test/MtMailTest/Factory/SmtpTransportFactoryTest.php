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
use MtMail\Factory\SmtpTransportFactory;
use Zend\Mail\Transport\Smtp;

class SmtpTransportFactoryTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateService()
    {
        $locator = $this->prophesize(ContainerInterface::class);
        $locator->get('Configuration')->willReturn(
            [
                'mt_mail' => [
                    'transport_options' => [
                        'host' => 'some-host.com',
                        'connection_class' => 'login',
                        'connection_config' => [
                            'username' => 'user',
                            'password' => 'pass',
                            'ssl' => 'tls',
                        ],
                    ],
                ],
            ]
        );
        $factory = new SmtpTransportFactory();
        $service = $factory($locator->reveal());
        $this->assertInstanceOf(Smtp::class, $service);
        $this->assertEquals('some-host.com', $service->getOptions()->getHost());
        $this->assertEquals('login', $service->getOptions()->getConnectionClass());
        $this->assertEquals(
            [
                'username' => 'user',
                'password' => 'pass',
                'ssl' => 'tls',
            ],
            $service->getOptions()->getConnectionConfig()
        );
    }
}
