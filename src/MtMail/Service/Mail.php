<?php

namespace MtMail\Service;


use MtMail\Renderer\RendererInterface;
use MtMail\Template\TemplateInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\View\Model\ModelInterface;

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

    public function __construct(RendererInterface $renderer, TransportInterface $transport)
    {
        $this->renderer = $renderer;
        $this->transport = $transport;
    }


    /**
     * @param TemplateInterface $template
     * @param ModelInterface $viewModel
     * @return Message
     */
    public function compose(TemplateInterface $template, ModelInterface $viewModel = null)
    {
        $composedViewModel = $template->getDefaultViewModel();
        if (null !== $viewModel) {
            $composedViewModel->setVariables($viewModel->getVariables());
        }

        $body = $this->renderer->render($composedViewModel);

        $message = new Message();
        $message->setBody($body);

        if ($composedViewModel->getVariable('subject')) {
            $message->setSubject($composedViewModel->getVariable('subject'));
        }

        return $message;
    }

    /**
     * @param Message $message
     * @return void
     */
    public function send(Message $message)
    {
        $this->transport->send($message);
    }
}