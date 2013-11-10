<?php

namespace MtMail\Service;


use MtMail\Event\ComposerEvent;
use MtMail\Exception\InvalidArgumentException;
use MtMail\Renderer\RendererInterface;
use MtMail\Template\Simple;
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
     * @return ComposerEvent
     */
    protected function getEvent()
    {
        $event = new ComposerEvent();
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
     * @param TemplateInterface|string $template
     * @param array $headers
     * @param ModelInterface $viewModel
     * @throws InvalidArgumentException if template is not string nor TemplateInterface
     * @return Message
     */
    public function compose($template, array $headers = null, ModelInterface $viewModel = null)
    {
        if (is_string($template)) {
            $template = new Simple($template);
        } elseif (!$template instanceof TemplateInterface) {
            throw new InvalidArgumentException("template should be either string, or object implementing TemplateInterface");
        }

        $em = $this->getEventManager();
        $event = $this->getEvent();
        $event->setTarget($this);

        $composedViewModel = $template->getDefaultViewModel();
        if (null !== $viewModel) {
            $composedViewModel->setVariables($viewModel->getVariables());
        }

        $event->setViewModel($composedViewModel);
        $em->trigger(ComposerEvent::EVENT_RENDER_PRE, $event);
        $html = $this->renderer->render($event->getViewModel());
        $message = $this->createHtmlMessage($html, $headers);
        $event->setMessage($message);
        $em->trigger(ComposerEvent::EVENT_RENDER_POST, $event);

        return $message;
    }

}