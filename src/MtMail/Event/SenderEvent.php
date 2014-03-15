<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Event;

use Zend\EventManager\Event;
use Zend\Mail\Message;

class SenderEvent extends Event
{
    /**#@+
     * Mail events
     */
    const EVENT_SEND_PRE = 'send.pre';
    const EVENT_SEND_POST = 'send.post';
    /**#@-*/

    /**
     * @var Message
     */
    protected $message;

    /**
     * @param  \Zend\Mail\Message $message
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
}
