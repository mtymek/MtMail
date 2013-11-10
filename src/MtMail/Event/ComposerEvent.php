<?php

namespace MtMail\Event;

use Zend\EventManager\Event;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;

class ComposerEvent extends Event
{
    /**#@+
     * Mail events
     */
    const EVENT_RENDER_PRE = 'render.pre';
    const EVENT_RENDER_POST = 'render.post';
    /**#@-*/

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var ViewModel
     */
    protected $viewModel;

    /**
     * @param \Zend\Mail\Message $message
     * @return self
     */
    public function setMessage(Message $message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return \Zend\Mail\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param \Zend\View\Model\ViewModel $viewModel
     * @return self
     */
    public function setViewModel($viewModel)
    {
        $this->viewModel = $viewModel;
        return $this;
    }

    /**
     * @return \Zend\View\Model\ViewModel
     */
    public function getViewModel()
    {
        return $this->viewModel;
    }

}