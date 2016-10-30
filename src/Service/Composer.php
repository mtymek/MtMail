<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Service;

use MtMail\Event\ComposerEvent;
use MtMail\Exception\InvalidArgumentException;
use MtMail\Renderer\RendererInterface;
use MtMail\Template\HtmlTemplateInterface;
use MtMail\Template\TemplateInterface;
use MtMail\Template\TextTemplateInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;

class Composer implements EventManagerAwareInterface
{
    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     * Class constructor
     *
     * @param RendererInterface $renderer
     */
    public function __construct(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
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
     * @param  \MtMail\Renderer\RendererInterface $renderer
     * @return self
     */
    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * @return \MtMail\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Create and return event used by compose and send methods
     *
     * @return ComposerEvent
     */
    protected function getEvent()
    {
        $event = new ComposerEvent();
        $event->setTarget($this);

        return $event;
    }

    /**
     * Build e-mail message
     *
     * @param  TemplateInterface        $template
     * @param  array                    $headers
     * @param  ModelInterface           $viewModel
     * @throws InvalidArgumentException if template is not string nor TemplateInterface
     * @return Message
     */
    public function compose(array $headers, TemplateInterface $template, ModelInterface $viewModel = null)
    {
        if (null == $viewModel) {
            $viewModel = new ViewModel();
        }

        $event = $this->getEvent();
        $event->setTemplate($template);
        $em = $this->getEventManager();

        // 1. Trigger pre event
        $event->setName(ComposerEvent::EVENT_COMPOSE_PRE);
        $em->triggerEvent($event);

        // 2. inject headers
        $event->setName(ComposerEvent::EVENT_HEADERS_PRE);
        $em->triggerEvent($event);
        foreach ($headers as $name => $value) {
            switch ($name) {
                case "to":
                    $value = explode(",", $value);
                    if (!is_array($value)) {
                        $tmp = $value;
                        $value = [];
                        $value[] = $tmp;
                    }
                    foreach ($value as $item) {
                        $event->getMessage()->addTo($item);
                    }
                    break;
                case "from":
                    $value = explode(",", $value);
                    if (!is_array($value)) {
                        $tmp = $value;
                        $value = [];
                        $value[] = $tmp;
                    }
                    foreach ($value as $item) {
                        $event->getMessage()->addFrom($item);
                    }
                    break;
                case "bcc":
                    $value = explode(",", $value);
                    if (!is_array($value)) {
                        $tmp = $value;
                        $value = [];
                        $value[] = $tmp;
                    }
                    foreach ($value as $item) {
                        $event->getMessage()->addBcc($item);
                    }
                    break;
                case "cc":
                    $value = explode(",", $value);
                    if (!is_array($value)) {
                        $tmp = $value;
                        $value = [];
                        $value[] = $tmp;
                    }
                    foreach ($value as $item) {
                        $event->getMessage()->addCc($item);
                    }
                    break;
                default:
                    $event->getMessage()->getHeaders()->addHeaderLine($name, $value);
            }
        }
        $event->setName(ComposerEvent::EVENT_HEADERS_POST);
        $em->triggerEvent($event);

        // prepare placeholder for message body
        $body = new MimeMessage();

        // 3. Render plain text template
        if ($template instanceof TextTemplateInterface) {
            $textViewModel = clone $viewModel;
            $textViewModel->setTemplate($template->getTextTemplateName());
            $event->setViewModel($textViewModel);

            $event->setName(ComposerEvent::EVENT_TEXT_BODY_PRE);
            $em->triggerEvent($event);

            $text = new MimePart($this->renderer->render($event->getViewModel()));
            $text->type = 'text/plain';
            $text->charset = $event->getMessage()->getHeaders()->getEncoding();
            $body->addPart($text);

            $event->setName(ComposerEvent::EVENT_TEXT_BODY_POST);
            $em->triggerEvent($event);
        }

        // 4. Render HTML template
        if ($template instanceof HtmlTemplateInterface) {
            $htmlViewModel = clone $viewModel;
            $htmlViewModel->setTemplate($template->getHtmlTemplateName());
            $event->setViewModel($htmlViewModel);

            $event->setName(ComposerEvent::EVENT_HTML_BODY_PRE);
            $em->triggerEvent($event);

            $html = new MimePart($this->renderer->render($event->getViewModel()));
            $html->type = 'text/html';
            $html->charset = $event->getMessage()->getHeaders()->getEncoding();
            $body->addPart($html);

            $event->setName(ComposerEvent::EVENT_HTML_BODY_POST);
            $em->triggerEvent($event);
        }

        // 5. inject body into message
        $event->setBody($body);
        $event->getMessage()->setBody($body);

        // 6. set multipart/alternative when both versions are available
        if ($template instanceof TextTemplateInterface && $template instanceof HtmlTemplateInterface) {
            $event->getMessage()->getHeaders()->get('content-type')->setType('multipart/alternative')
                ->addParameter('boundary', $body->getMime()->boundary());
        }

        $event->setName(ComposerEvent::EVENT_COMPOSE_POST);
        $em->triggerEvent($event);

        return $event->getMessage();
    }

    public function attachments(Message $message, array $attachments)
    {
        if (sizeof($attachments) > 0) {
            $type = $message->getHeaders()->get('content-type')->getType();
            if ($type != 'multipart/related') {
                $parts = $message->getBody()->getParts();
                $htmlPart = null;
                $textPart = null;

                // locate HTML body
                foreach ($parts as $part) {
                    foreach ($part->getHeadersArray() as $header) {
                        if ($header[0] == 'Content-Type' && strpos($header[1], 'text/html') === 0) {
                            $htmlPart = $part;
                        } elseif ($header[0] == 'Content-Type' && strpos($header[1], 'text/plain') === 0) {
                            $textPart = $part;
                        }
                    }
                }

                if (!empty($textPart) && !empty($htmlPart)) {
                    $content = new MimeMessage();
                    $content->addPart($textPart);
                    $content->addPart($htmlPart);
                    $contentPart = new MimePart($content->generateMessage());
                    $contentPart->type = "multipart/alternative;\n boundary=\"" .
                        $content->getMime()->boundary() . '"';
                    $message->getBody()->setParts([$contentPart]);
                } else {
                    if (empty($textPart)) {
                        $message->getBody()->setParts([$htmlPart]);
                    } else {
                        $message->getBody()->setParts([$textPart]);
                    }
                }
            }

            foreach ($attachments as $attachment) {
                if (is_readable($attachment)) {
                    $pathParts          = pathinfo($attachment);
                    $at = new MimePart(file_get_contents($attachment));
                    $at->type           = $this->getType($pathParts['extension']);
                    $at->filename       = $pathParts['filename'];
                    $at->disposition    = Mime::DISPOSITION_ATTACHMENT;

                    $message->getBody()->addPart($at);
                }
            }

            // force multipart/alternative content type
            if ($type != 'multipart/related') {
                $message->getHeaders()->get('content-type')->setType('multipart/related')
                    ->addParameter('boundary', $event->getBody()->getMime()->boundary());
            }
        }
        return $message;
    }

    private function getType($ext)
    {
        switch (strtolower($ext)) {
            case "pdf":
                $type = 'application/pdf';
                break;
            case "doc":
                $type = "application/msword";
                break;
            case "docx":
                $type = "application/vnd.openxmlformats-officedocument.wordprocessingml.document";
                break;
            case "odt":
                $type =  "application/vnd.oasis.opendocument.text";
                break;
            case "gzip":
                $type =  'application/gzip';
                break;
            case "txt":
                $type= 'application/text';
                break;
            case "zip":
                $type = 'application/zip';
                break;
            default:
                $type = Mime::TYPE_OCTETSTREAM;
        }
        return $type;
    }
}
