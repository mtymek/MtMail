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
        /* suppression de la balise title */
        $posStyle = strpos(strtolower($string), "<title");
        $posEndStyle = strpos(strtolower($string), "</title>", $posStyle + 1);
        $string = substr($string, 0, $posStyle - 1) . substr($string, $posEndStyle + 8);

        while ($posStyle = strpos(strtolower($string), "<style")) {
            /* suppression des balises style présente dans le souce html */
            $posEndStyle = strpos(strtolower($string), "</style>", $posStyle + 1);
            $string = substr($string, 0, $posStyle - 1) . substr($string, $posEndStyle + 8);
        }

        $string =  preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
        /* suppression des espace en début et fin de source convertis */
        $string = trim(strip_tags($string));

        return $string;
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
        $htmlPart = null;
        $textBody = null;

        // locate HTML body
        foreach ($parts as $part) {
            foreach ($part->getHeadersArray() as $header) {
                if ($header[0] == 'Content-Type' && strpos($header[1], 'text/html') === 0) {
                    $htmlPart = $part;
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
        $textPart = new MimePart($textBody);
        $textPart->type = 'text/plain';
        $event->getBody()->setParts([$textPart, $htmlPart]);

        // force multipart/alternative content type
        $event->getMessage()->getHeaders()->get('content-type')->setType('multipart/alternative')
            ->addParameter('boundary', $event->getBody()->getMime()->boundary());
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
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_COMPOSE_POST, [$this, 'generateTextBody']);
    }
}
