<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Event;

use MtMail\Template\TemplateInterface;
use Zend\EventManager\Event;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;
use Zend\Mime\Message as MimeMessage;

class ComposerEvent extends Event
{
    /**#@+
     * Mail events
     */
    const EVENT_COMPOSE_PRE = 'compose.pre';
    const EVENT_COMPOSE_POST = 'compose.post';
    const EVENT_HEADERS_PRE = 'headers.pre';
    const EVENT_HEADERS_POST = 'headers.post';
    const EVENT_HTML_BODY_PRE = 'html_body.pre';
    const EVENT_HTML_BODY_POST = 'html_body.post';
    const EVENT_TEXT_BODY_PRE = 'text_body.pre';
    const EVENT_TEXT_BODY_POST = 'text_body.post';
    /**#@-*/

    /**
     * @var TemplateInterface
     */
    protected $template;

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var MimeMessage
     */
    protected $body;

    /**
     * @var ModelInterface
     */
    protected $viewModel;

    /**
     * @param  Message $message
     * @return self
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return Message
     */
    public function getMessage()
    {
        if (!$this->message instanceof Message) {
            $this->setMessage(new Message);
        }

        return $this->message;
    }

    /**
     * @param  ModelInterface $viewModel
     * @return self
     */
    public function setViewModel(ModelInterface $viewModel)
    {
        $this->viewModel = $viewModel;

        return $this;
    }

    /**
     * @return ModelInterface
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

    /**
     * @param  \Zend\Mime\Message $body
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return \Zend\Mime\Message
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param TemplateInterface $template
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * @return TemplateInterface
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
