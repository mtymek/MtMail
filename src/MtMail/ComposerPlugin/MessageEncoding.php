<?php

namespace MtMail\ComposerPlugin;


use MtMail\Event\ComposerEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\View\Model\ViewModel;

class MessageEncoding extends AbstractListenerAggregate implements PluginInterface
{

    /**
     * @var string
     */
    protected $encoding;


    /**
     * Set encoding of message inside event
     *
     * @param ComposerEvent $event
     */
    public function setMessageEncoding(ComposerEvent $event)
    {
        $event->getMessage()->setEncoding($this->encoding);
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_COMPOSE_PRE, array($this, 'setMessageEncoding'));
    }

    /**
     * @param string $encoding
     * @return self
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return $this->encoding;
    }

}
