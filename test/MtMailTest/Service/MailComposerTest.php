<?php

namespace MtMailTest\Service;

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

}
