<?php

namespace MtMailTest\Service;

use MtMail\Event\ComposerEvent;
use MtMail\Factory\MailComposerFactory;
use MtMail\Service\MailComposer;
use MtMailTest\Test\Template;
use Zend\EventManager\EventManager;

class MailComposerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MailComposer
     */
    protected $service;

    public function setUp()
    {
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface');
        $this->service = new MailComposer($renderer);
    }

    public function testComposeRendersViewModelAndAssignsResultToMailBody()
    {
        $template = new Template();

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new MailComposer($renderer);
        $message = $service->compose($template);
        $this->assertEquals('MAIL_BODY', $message->getBody()->getPartContent(0));
    }

    public function testComposeRendersViewModelAndAssignsSubjectIfProvidedByViewModel()
    {
        $template = new Template();

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new MailComposer($renderer);
        $message = $service->compose($template, array('subject' => 'MAIL_SUBJECT'));
        $this->assertEquals('MAIL_BODY', $message->getBody()->getPartContent(0));
        $this->assertEquals('MAIL_SUBJECT', $message->getSubject());
    }

    public function testServiceIsEventManagerAware()
    {
        $em = new EventManager();
        $this->service->setEventManager($em);
        $this->assertEquals($em, $this->service->getEventManager());
    }

    public function testComposeTriggersEvents()
    {
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $em = $this->getMock('Zend\EventManager\EventManager', array('trigger'));
        $em->expects($this->at(0))->method('trigger')->with(ComposerEvent::EVENT_RENDER_PRE, $this->isInstanceOf('MtMail\Event\ComposerEvent'));
        $em->expects($this->at(1))->method('trigger')->with(ComposerEvent::EVENT_RENDER_POST, $this->isInstanceOf('MtMail\Event\ComposerEvent'));

        $service = new MailComposer($renderer);
        $service->setEventManager($em);
        $template = new Template();
        $service->compose($template);
    }

}
