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
use MtMail\Template\TextProviderInterface;
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

        // 1. create message
        $message = new Message();
        $event->setMessage($message);
        $em->trigger(ComposerEvent::EVENT_COMPOSE_PRE, $event);

        // 2. inject headers
        $em->trigger(ComposerEvent::EVENT_HEADERS_PRE, $event);
        foreach ($headers as $name => $value) {
            $message->getHeaders()->addHeaderLine($name, $value);
        }
        $em->trigger(ComposerEvent::EVENT_HEADERS_POST, $event);

        // prepare placeholder for message body
        $body = new MimeMessage();

        // 3. Render HTML template
        if ($template instanceof HtmlTemplateInterface) {
            $htmlViewModel = clone $viewModel;
            $htmlViewModel->setTemplate($template->getHtmlTemplateName());
            $event->setViewModel($htmlViewModel);

            $em->trigger(ComposerEvent::EVENT_HTML_BODY_PRE, $event);

            $html = new MimePart($this->renderer->render($event->getViewModel()));
            $html->type = 'text/html';
            $body->addPart($html);

            $em->trigger(ComposerEvent::EVENT_HTML_BODY_POST, $event);
        }

        // 4. Render plain text template
        if ($template instanceof TextTemplateInterface || $template instanceof TextProviderInterface) {
            $em->trigger(ComposerEvent::EVENT_TEXT_BODY_PRE, $event);
            if ($template instanceof TextTemplateInterface) {
                $textViewModel = clone $viewModel;
                $textViewModel->setTemplate($template->getTextTemplateName());
                $event->setViewModel($textViewModel);
                $text = $this->renderer->render($event->getViewModel());
            } else {
                $text = $template->getText();
            }
            $text = new MimePart($text);
            $text->type = 'text/plain';
            $body->addPart($text);
            $em->trigger(ComposerEvent::EVENT_TEXT_BODY_POST, $event);
        }

        // 5. inject body into message
        $event->setBody($body);
        $message->setBody($body);

        $em->trigger(ComposerEvent::EVENT_COMPOSE_POST, $event);

        return $message;
    }
}
