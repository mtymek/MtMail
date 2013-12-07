<?php

namespace MtMailTest\Service;

use MtMail\Event\ComposerEvent;
use MtMail\Service\Mail;
use MtMailTest\Test\Template;
use Zend\EventManager\EventManager;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;

class MailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mail
     */
    protected $service;

    public function testSendProxiesToSender()
    {
        $message = new Message();
        $sender = $this->getMock('MtMail\Service\MailSender', array('send'), array(), '', false);
        $sender->expects($this->once())->method('send')->with($message);
        $composer = $this->getMock('MtMail\Service\MailComposer', array(), array(), '', false);
        $service = new Mail($composer, $sender);
        $service->send($message);
    }

    public function testComposeProxiesToComposer()
    {
        $sender = $this->getMock('MtMail\Service\MailSender', array(), array(), '', false);
        $template = $this->getMock('MtMail\Template\TemplateInterface');
        $composer = $this->getMock('MtMail\Service\MailComposer', array('compose'), array(), '', false);
        $composer->expects($this->once())->method('compose')
            ->with(
                array('to' => 'johndoe@domain.com'), $template, $this->isInstanceOf('Zend\View\Model\ModelInterface')
            );
        $service = new Mail($composer, $sender);
        $service->compose(array('to' => 'johndoe@domain.com'), $template);
    }

}
