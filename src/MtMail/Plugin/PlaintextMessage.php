<?php

namespace MtMail\Plugin;

use MtMail\Event\ComposerEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mime\Part as MimePart;

class PlaintextMessage extends AbstractListenerAggregate implements PluginInterface
{

    /**
     * (c) http://php.net/nl2br#86678
     *
     * @param  $string
     * @return mixed
     */
    private function br2nl($string)
    {
        return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
    }

    /**
     * Create text version of HTML message by changing line breaks to newlines and
     * stripping tags.
     *
     * @param ComposerEvent $event
     */
    public function generateTextBody(ComposerEvent $event)
    {
        $parts = $event->getBody()->getParts();

        $htmlBody = null;
        $textBody = null;

        // locate HTML body
        foreach ($parts as $part) {
            foreach ($part->getHeadersArray() as $header) {
                if ($header[0] == 'Content-Type' && strpos($header[1], 'text/html') === 0) {
                    $htmlBody = $part->getRawContent();
                } elseif ($header[0] == 'Content-Type' && strpos($header[1], 'text/plain') === 0) {
                    $textBody = $part->getRawContent();
                }
            }
        }

        if ($textBody || !$htmlBody) {
            // can only work if HTML body exists and text doesn't
            return;
        }

        // create and insert text body
        $textBody = strip_tags($this->br2nl($htmlBody));
        $text = new MimePart($textBody);
        $text->type = 'text/plain';
        $event->getBody()->addPart($text);
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
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_COMPOSE_POST, array($this, 'generateTextBody'));
    }

}
