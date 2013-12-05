<?php

namespace MtMail\Service;

use MtMail\Exception\InvalidArgumentException;
use MtMail\Template\SimpleHtml;
use MtMail\Template\TemplateInterface;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;

class Mail
{

    /**
     * @var MailComposer
     */
    protected $composer;

    /**
     * @var MailSender
     */
    protected $sender;

    /**
     * Class constructor
     *
     * @param MailComposer $composer
     * @param MailSender $sender
     */
    public function __construct(MailComposer $composer, MailSender $sender)
    {
        $this->composer = $composer;
        $this->sender = $sender;
    }

    /**
     * Send e-mail - wrapper for Sender class
     *
     * @param Message $message
     */
    public function send(Message $message)
    {
        $this->sender->send($message);
    }

    /**
     * @param $template
     * @param ModelInterface $viewModel
     * @param array $headers
     * @throws InvalidArgumentException
     * @return Message
     */
    public function compose($template, ModelInterface $viewModel = null, array $headers = array())
    {
        if (is_string($template)) {
            $template = new SimpleHtml($template);
        } elseif (!$template instanceof TemplateInterface) {
            throw new InvalidArgumentException("template should be either string, or object implementing TemplateInterface");
        }

        return $this->composer->compose($template, $viewModel, $headers);
    }

}