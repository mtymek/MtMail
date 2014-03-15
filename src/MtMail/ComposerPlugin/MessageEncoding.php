<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\ComposerPlugin;

use MtMail\Event\ComposerEvent;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;

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
     * @param  string $encoding
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
