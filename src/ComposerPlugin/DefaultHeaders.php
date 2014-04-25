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
use MtMail\Template\HeadersProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;

class DefaultHeaders extends AbstractListenerAggregate implements PluginInterface
{

    /**
     * @var array
     */
    protected $headers = array();

    /**
     * @param ComposerEvent $event
     */
    public function injectDefaultHeaders(ComposerEvent $event)
    {
        $message = $event->getMessage();
        foreach ($this->headers as $header => $value) {
            $message->getHeaders()->addHeaderLine($header, $value);
        }

        if ($event->getTemplate() instanceof HeadersProviderInterface) {
            foreach ($event->getTemplate()->getHeaders() as $header => $value) {
                $message->getHeaders()->addHeaderLine($header, $value);
            }
        }
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
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_HEADERS_PRE, array($this, 'injectDefaultHeaders'));
    }

    /**
     * @param  array $headers
     * @return self
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
