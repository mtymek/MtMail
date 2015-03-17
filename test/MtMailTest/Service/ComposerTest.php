<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Event\ComposerEvent;
use MtMail\Service\Composer;
use MtMailTest\Test\HtmlTemplate;
use MtMailTest\Test\TextTemplate;
use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;

class ComposerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Composer
     */
    protected $service;

    public function setUp()
    {
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface');
        $this->service = new Composer($renderer);
    }

    public function testRendererIsMutable()
    {
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface');
        $this->assertEquals($renderer, $this->service->setRenderer($renderer)->getRenderer());
    }

    public function testComposeRendersViewModelAndAssignsResultToMailBody()
    {
        $template = new TextTemplate();

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new Composer($renderer);
        $message = $service->compose(array(), $template, new ViewModel());
        $this->assertEquals('MAIL_BODY', $message->getBody()->getPartContent(0));
    }

    public function testComposeRendersViewModelAndAssignsSubjectIfProvidedByViewModel()
    {
        $template = new HtmlTemplate();

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new Composer($renderer);
        $message = $service->compose(array('subject' => 'MAIL_SUBJECT'), $template, new ViewModel());
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
        $em->expects($this->at(0))->method('trigger')->with(ComposerEvent::EVENT_COMPOSE_PRE, $this->isInstanceOf('MtMail\Event\ComposerEvent'));
        $em->expects($this->at(1))->method('trigger')->with(ComposerEvent::EVENT_HEADERS_PRE, $this->isInstanceOf('MtMail\Event\ComposerEvent'));
        $em->expects($this->at(2))->method('trigger')->with(ComposerEvent::EVENT_HEADERS_POST, $this->isInstanceOf('MtMail\Event\ComposerEvent'));
        $em->expects($this->at(3))->method('trigger')->with(ComposerEvent::EVENT_HTML_BODY_PRE, $this->isInstanceOf('MtMail\Event\ComposerEvent'));
        $em->expects($this->at(4))->method('trigger')->with(ComposerEvent::EVENT_HTML_BODY_POST, $this->isInstanceOf('MtMail\Event\ComposerEvent'));
        $em->expects($this->at(5))->method('trigger')->with(ComposerEvent::EVENT_COMPOSE_POST, $this->isInstanceOf('MtMail\Event\ComposerEvent'));

        $service = new Composer($renderer);
        $service->setEventManager($em);
        $template = new HtmlTemplate();
        $service->compose(array(), $template, new ViewModel());
    }

    public function testHtmlBodyPreEventAllowsReplacingViewModel()
    {
        $replacement = new ViewModel();
        $replacement->setTemplate('some_template.phtml');
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->equalTo($replacement))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new Composer($renderer);
        $template = new HtmlTemplate();

        $service->getEventManager()->attach(ComposerEvent::EVENT_HTML_BODY_PRE, function ($event) use ($replacement) {
                $event->setViewModel($replacement);
            });

        $service->compose(array(), $template, new ViewModel());
    }

    public function testTextTemplateHasCorrectCharset()
    {
        $template = new TextTemplate();
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $service = new Composer($renderer);

        $message = $service->compose(array(), $template, new ViewModel());

        $parts = $message->getBody()->getParts();
        $textPart = $parts[0];
        $this->assertTrue(isset($textPart->charset));
        $this->assertSame($message->getHeaders()->getEncoding(), $textPart->charset);
    }
}
