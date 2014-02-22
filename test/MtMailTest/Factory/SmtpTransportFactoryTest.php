<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Factory;

use MtMail\Factory\SmtpTransportFactory;

class SmtpTransportFactoryTest extends \PHPUnit_Framework_TestCase
{

    public function testCreateService()
    {
        $locator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', array('get', 'has'));
        $locator->expects($this->once())->method('get')
            ->with('Configuration')->will(
                $this->returnValue(
                    array(
                        'mt_mail' => array(
                            'transport_options' => array(
                                'host' => 'some-host.com',
                                'connection_class' => 'login',
                                'connection_config' => array(
                                    'username' => 'user',
                                    'password' => 'pass',
                                    'ssl' => 'tls',
                                ),
                            ),
                        ),
                    )
                )
            );
        $factory = new SmtpTransportFactory();
        $service = $factory->createService($locator);
        $this->assertInstanceOf('Zend\Mail\Transport\Smtp', $service);
        $this->assertEquals('some-host.com', $service->getOptions()->getHost());
        $this->assertEquals('login', $service->getOptions()->getConnectionClass());
        $this->assertEquals(
            array(
                'username' => 'user',
                'password' => 'pass',
                'ssl' => 'tls',
            )
            ,
            $service->getOptions()->getConnectionConfig()
        );
    }

}
