<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
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
use Zend\EventManager\EventManagerInterface;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\ViewModel;

class Composer
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
            $event->getMessage()->getHeaders()->addHeaderLine($name, $value);
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
}
