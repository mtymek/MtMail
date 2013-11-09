<?php

namespace MtMail\Service;


use MtMail\Renderer\RendererInterface;
use MtMail\Template\TemplateInterface;
use Zend\Mail\Headers;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\View\Model\ModelInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class Mail implements MailInterface
{
    /**
     * @var RendererInterface
     */
    protected $renderer;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * Class constructor
     *
     * @param RendererInterface $renderer
     * @param TransportInterface $transport
     */
    public function __construct(RendererInterface $renderer, TransportInterface $transport)
    {
        $this->renderer = $renderer;
        $this->transport = $transport;
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

    /**
     * Send message
     *
     * @param Message $message
     * @return void
     */
    public function send(Message $message)
    {
        $this->transport->send($message);
    }
}