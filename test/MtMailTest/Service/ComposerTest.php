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
use Prophecy\Argument;
use Zend\EventManager\EventManager;
use Zend\View\Model\ViewModel;

class ComposerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Composer
     */
    protected $service;

    /**
     * @var array
     */
    protected $composeEventsTriggered = [];

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

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', ['render']);
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new Composer($renderer);
        $message = $service->compose([], $template, new ViewModel());
        $this->assertEquals('MAIL_BODY', $message->getBody()->getPartContent(0));
    }

    public function testComposeRendersViewModelAndAssignsSubjectIfProvidedByViewModel()
    {
        $template = new HtmlTemplate();

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', ['render']);
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new Composer($renderer);
        $message = $service->compose(['subject' => 'MAIL_SUBJECT'], $template, new ViewModel());
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
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', ['render']);
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $em = new EventManager();
        $listener = function ($event) {
            $this->assertInstanceof(
                ComposerEvent::class, 
                $event, 
                'Failed asserting event instance of ' . get_class($event) . ' is of type ' . ComposerEvent::class
            );
            $this->composeEventsTriggered[] = $event->getName();
        };

        $em->attach(
            ComposerEvent::EVENT_COMPOSE_PRE,
            $listener
        );
        $em->attach(
            ComposerEvent::EVENT_HEADERS_PRE,
            $listener
        );
        $em->attach(
            ComposerEvent::EVENT_HEADERS_POST,
            $listener
        );
        $em->attach(
            ComposerEvent::EVENT_HTML_BODY_PRE,
            $listener
        );
        $em->attach(
            ComposerEvent::EVENT_HTML_BODY_POST,
            $listener
        );
        $em->attach(
            ComposerEvent::EVENT_COMPOSE_POST,
            $listener
        );

        $service = new Composer($renderer);
        $service->setEventManager($em);
        $template = new HtmlTemplate();
        $service->compose([], $template, new ViewModel());

        $this->assertEquals(
            [
                ComposerEvent::EVENT_COMPOSE_PRE,
                ComposerEvent::EVENT_HEADERS_PRE,
                ComposerEvent::EVENT_HEADERS_POST,
                ComposerEvent::EVENT_HTML_BODY_PRE,
                ComposerEvent::EVENT_HTML_BODY_POST,
                ComposerEvent::EVENT_COMPOSE_POST,
            ],
            $this->composeEventsTriggered
        );
    }

    public function testHtmlBodyPreEventAllowsReplacingViewModel()
    {
        $replacement = new ViewModel();
        $replacement->setTemplate('some_template.phtml');
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', ['render']);
        $renderer->expects($this->once())->method('render')->with($this->equalTo($replacement))
            ->will($this->returnValue('MAIL_BODY'));

        $service = new Composer($renderer);
        $template = new HtmlTemplate();

        $service->getEventManager()->attach(ComposerEvent::EVENT_HTML_BODY_PRE, function ($event) use ($replacement) {
                $event->setViewModel($replacement);
        });

        $service->compose([], $template, new ViewModel());
    }

    public function testTextTemplateHasCorrectCharset()
    {
        $viewModel = new ViewModel();
        $template = new TextTemplate();
        $renderer = $this->prophesize('MtMail\Renderer\RendererInterface');
        $renderer->render(Argument::type('Zend\View\Model\ViewModel'))->willReturn('BODY');
        $service = new Composer($renderer->reveal());

        $message = $service->compose([], $template, $viewModel);

        $parts = $message->getBody()->getParts();
        $textPart = $parts[0];
        $this->assertTrue(isset($textPart->charset));
        $this->assertSame($message->getHeaders()->getEncoding(), $textPart->charset);
    }
}
