<?php

namespace MtMail\Service;

use MtMail\Template\TemplateInterface;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;

interface MailInterface
{

    /**
     * @param TemplateInterface $template
     * @param ModelInterface $viewModel
     * @return Message
     */
    public function compose(TemplateInterface $template, ModelInterface $viewModel = null);

    /**
     * @param Message $message
     * @return mixed
     */
    public function send(Message $message);
}
