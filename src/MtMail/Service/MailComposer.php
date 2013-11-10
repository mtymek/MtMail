<?php

namespace MtMail\Service;


use MtMail\Event\MailEvent;
use MtMail\Renderer\RendererInterface;
use MtMail\Template\TemplateInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Mail\Headers;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class MailComposer implements EventManagerAwareInterface
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
     * Create mail message with HTML mime part
     *
     * @param $html
     * @param array $headers
     * @return Message
     */
    private function createHtmlMessage($html, array $headers = null)
    {
        $message = new Message();

        if (null !== $headers) {
            $mailHeaders = new Headers();
            $mailHeaders->addHeaders($headers);
            $message->setHeaders($mailHeaders);
        }

        $message->setEncoding('UTF-8');
        $body = new MimePart($html);
        $body->type = 'text/html';
        $mimeMessage = new MimeMessage();
        $mimeMessage->addPart($body);

        $message->setBody($mimeMessage);
        return $message;
    }

    /**
     * Build e-mail message
     *
     * @param TemplateInterface $template
     * @param array $headers
     * @param ModelInterface $viewModel
     * @return Message
     */
    public function compose(TemplateInterface $template, array $headers = null, ModelInterface $viewModel = null)
    {
        $composedViewModel = $template->getDefaultViewModel();
        if (null !== $viewModel) {
            $composedViewModel->setVariables($viewModel->getVariables());
        }

        $html = $this->renderer->render($composedViewModel);
        $message = $this->createHtmlMessage($html, $headers);

        return $message;
    }

}