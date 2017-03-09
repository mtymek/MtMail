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
use Prophecy\Argument;
use Zend\EventManager\EventManager;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class SenderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Sender
     */
    protected $service;

    /**
     * @var array
     */
    protected $senderEventsTriggered = [];

    public function setUp()
    {
        $transport = $this->prophesize(TransportInterface::class);
        $this->service = new Sender($transport->reveal());
    }

    public function testSendPassesMessageToTransportObject()
    {
        $message = new Message();
        $transport = $this->prophesize(TransportInterface::class);
        $transport->send($message)->shouldBeCalled();
        $service = new Sender($transport->reveal());
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
        $transport = $this->prophesize(TransportInterface::class);
        $transport->send(Argument::type(Message::class))->shouldBeCalled();

        $em = new EventManager();
        $listener = function ($event) {
            $this->assertInstanceOf(
                SenderEvent::class,
                $event,
                'Failed asserting event instance of ' . get_class($event) . 'is of type ' . SenderEvent::class
            );
            $this->senderEventsTriggered[] = $event->getName();
        };

        $em->attach(
            SenderEvent::EVENT_SEND_PRE,
            $listener
        );
        $em->attach(
            SenderEvent::EVENT_SEND_POST,
            $listener
        );

        $service = new Sender($transport->reveal());
        $service->setEventManager($em);
        $message = new Message();
        $service->send($message);

        $this->assertEquals(
            [
                SenderEvent::EVENT_SEND_PRE,
                SenderEvent::EVENT_SEND_POST,
            ],
            $this->senderEventsTriggered
        );
    }
}
