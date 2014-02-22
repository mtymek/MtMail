<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Event\SenderEvent;
use MtMail\Service\Sender;
use Zend\EventManager\EventManager;
use Zend\Mail\Message;

class SenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Sender
     */
    protected $service;

    public function setUp()
    {
        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');
        $this->service = new Sender($transport);
    }

    public function testSendPassesMessageToTransportObject()
    {
        $message = new Message();
        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface', array('send'));
        $transport->expects($this->once())->method('send')
            ->with($message);
        $service = new Sender($transport);
        $service->send($message);
    }

    public function testServiceIsEventManagerAware()
    {
        $em = new EventManager();
        $this->service->setEventManager($em);
        $this->assertEquals($em, $this->service->getEventManager());
    }

    public function testSendTriggersEvents()
    {
        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface', array('send'));
        $transport->expects($this->once())->method('send')
            ->with($this->isInstanceOf('Zend\Mail\Message'));

        $em = $this->getMock('Zend\EventManager\EventManager', array('trigger'));
        $em->expects($this->at(0))->method('trigger')->with(SenderEvent::EVENT_SEND_PRE, $this->isInstanceOf('MtMail\Event\SenderEvent'));
        $em->expects($this->at(1))->method('trigger')->with(SenderEvent::EVENT_SEND_POST, $this->isInstanceOf('MtMail\Event\SenderEvent'));

        $service = new Sender($transport);
        $service->setEventManager($em);
        $message = new Message();
        $service->send($message);
    }
}
