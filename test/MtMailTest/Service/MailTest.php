<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Service\Mail;
use Zend\Mail\Message;

class MailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mail
     */
    protected $service;

    public function testSendProxiesToSender()
    {
        $message = new Message();
        $sender = $this->getMock('MtMail\Service\Sender', array('send'), array(), '', false);
        $sender->expects($this->once())->method('send')->with($message);
        $composer = $this->getMock('MtMail\Service\Composer', array(), array(), '', false);
        $templateManager = $this->getMock('MtMail\Service\TemplateManager');
        $service = new Mail($composer, $sender, $templateManager);
        $service->send($message);
    }

    public function testComposeProxiesToComposer()
    {
        $sender = $this->getMock('MtMail\Service\Sender', array(), array(), '', false);
        $template = $this->getMock('MtMail\Template\TemplateInterface');
        $composer = $this->getMock('MtMail\Service\Composer', array('compose'), array(), '', false);
        $composer->expects($this->once())->method('compose')
            ->with(
                array('to' => 'johndoe@domain.com'), $template, $this->isInstanceOf('Zend\View\Model\ModelInterface')
            );
        $templateManager = $this->getMock('MtMail\Service\TemplateManager');
        $service = new Mail($composer, $sender, $templateManager);
        $service->compose(array('to' => 'johndoe@domain.com'), $template);
    }

    public function testComposeTriesPullsTemplateFromManager()
    {
        $sender = $this->getMock('MtMail\Service\Sender', array(), array(), '', false);
        $composer = $this->getMock('MtMail\Service\Composer', array(), array(), '', false);
        $templateManager = $this->getMock('MtMail\Service\TemplateManager', array('has', 'get'));
        $templateManager->expects($this->once())->method('has')
            ->with('FooTemplate')->will($this->returnValue(true));
        $templateManager->expects($this->once())->method('get')
            ->with('FooTemplate')->will($this->returnValue($this->getMock('MtMail\Template\TemplateInterface')));
        $service = new Mail($composer, $sender, $templateManager);
        $service->compose(array('to' => 'johndoe@domain.com'), 'FooTemplate');
    }

    public function testComposeFallsBackToDefaultHtmlTemplate()
    {
        $sender = $this->getMock('MtMail\Service\Sender', array(), array(), '', false);
        $composer = $this->getMock('MtMail\Service\Composer', array('compose'), array(), '', false);
        $composer->expects($this->once())->method('compose')
            ->with(
                array('to' => 'johndoe@domain.com'), $this->isInstanceOf('MtMail\Template\SimpleHtml'), $this->isInstanceOf('Zend\View\Model\ModelInterface')
            );
        $templateManager = $this->getMock('MtMail\Service\TemplateManager', array('has'));
        $templateManager->expects($this->once())->method('has')
            ->with('FooTemplate')->will($this->returnValue(false));
        $service = new Mail($composer, $sender, $templateManager);
        $service->compose(array('to' => 'johndoe@domain.com'), 'FooTemplate', array());
    }
}
