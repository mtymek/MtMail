<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 21/10/16
 * Time: 14:05
 */

namespace MtMail\ComposerPlugin;

use MtMail\Event\ComposerEvent;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part;

class EmbeddingImages extends AbstractListenerAggregate implements PluginInterface
{
    public function addImages(ComposerEvent $event)
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

        if (!$htmlBody) {
            // can only work if HTML body exists
            return;
        }

        $doc = new \DOMDocument();
        $doc->loadHTML($htmlBody);
        $elements = $doc->getElementsByTagName('img');
        $elements =  (array) $elements;

        if (count($elements) > 0) {
            $attachement = [];
            foreach ($elements as $key => $element) {
                // traitement du nom de fichiers pour apprécier le contexte
                $file = $element->getAttribute("src");
                $filename = $file;
                $pos = strpos($file, "~");
                if ($pos !== false) {
                    $finUser = strpos($file, "/", $pos);
                    $filename = $_SERVER["CONTEXT_DOCUMENT_ROOT"].substr($file, $finUser);
                } else {
                    $pos = strpos($file, $_SERVER['HTTP_HOST']);
                    if ($pos !== false) {
                        $filename = $_SERVER["DOCUMENT_ROOT"].substr($file, $pos + strlen($_SERVER['HTTP_HOST']));
                    }
                }
                if (is_readable($filename)) {
                    $at = new MimePart(file_get_contents($filename));
                    $at->type = $this->mimeByExtension($filename);
                    $at->disposition = Mime::DISPOSITION_INLINE;
                    $at->encoding = Mime::ENCODING_BASE64;
                    $at->id = 'cid_' . md5_file($filename);
                    $htmlBody = str_replace($file, 'cid:' . $at->id, $htmlBody);
                    $attachement[] = $at;
                }
            }

            if (sizeof($attachement) > 0) {
                $textPart           = new MimePart($textBody);
                $textPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
                $textPart->type     = "text/plain; charset=UTF-8";

                $htmlPart           = new MimePart($htmlBody);
                $htmlPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
                $htmlPart->type     = "text/html; charset=UTF-8";

                $content = new MimeMessage();
                $content->addPart($textPart);
                $content->addPart($htmlPart);

                $contentPart = new Part($content->generateMessage());
                $contentPart->type = "multipart/alternative;\n boundary=\"" . $content->getMime()->boundary() . '"';

                $event->getBody()->setParts([$contentPart]);

                foreach ($attachement as $at) {
                    $event->getBody()->addPart($at);
                }

                // force multipart/alternative content type
                $event->getMessage()->getHeaders()->get('content-type')->setType('multipart/related')
                    ->addParameter('boundary', $event->getBody()->getMime()->boundary());
            }
        }
    }

    private function mimeByExtension($filename)
    {
        if (is_readable($filename)) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            switch ($extension) {
                case 'gif':
                    $type = 'image/gif';
                    break;
                case 'jpg':
                case 'jpeg':
                    $type = 'image/jpg';
                    break;
                case 'png':
                    $type = 'image/png';
                    break;
                default:
                    $type = 'application/octet-stream';
            }
        }

        return $type;
    }

    public function attach(EventManagerInterface $events, $priority = 2)
    {
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_COMPOSE_POST, [$this, 'addImages']);
    }
}
