<?php

namespace MtMail\Service;


use MtMail\Event\MailEvent;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

class MailSender implements EventManagerAwareInterface
{

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Class constructor
     *
     * @param TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return self
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->eventManager = new EventManager();
        }
        return $this->eventManager;
    }

    /**
     * Create and return event used by compose and send methods
     *
     * @return MailEvent
     */
    protected function getEvent()
    {
        $event = new MailEvent();
        $event->setTarget($this);
        return $event;
    }

    /**
     * Send message
     *
     * @param Message $message
     * @return void
     */
    public function send(Message $message)
    {
        $em = $this->getEventManager();
        $event = $this->getEvent();
        $event->setMessage($message);
        $em->trigger(MailEvent::EVENT_SEND_PRE, $event);
        $this->transport->send($message);
        $em->trigger(MailEvent::EVENT_SEND_POST, $event);
    }

}