<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Event\ComposerEvent;
use MtMail\Renderer\RendererInterface;
use MtMail\Service\Composer;
use MtMailTest\Test\HtmlTemplate;
use MtMailTest\Test\TextTemplate;
use Prophecy\Argument;
use Zend\EventManager\EventManager;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

class ComposerTest extends \PHPUnit\Framework\TestCase
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
        $renderer = $this->prophesize(RendererInterface::class);
        $this->service = new Composer($renderer->reveal());
    }

    public function testRendererIsMutable()
    {
        $renderer = $this->prophesize(RendererInterface::class);
        $this->assertEquals($renderer->reveal(), $this->service->setRenderer($renderer->reveal())->getRenderer());
    }

    public function testComposeRendersViewModelAndAssignsResultToMailBody()
    {
        $template = new TextTemplate();

        $renderer = $this->prophesize(RendererInterface::class);
        $renderer->render(Argument::type(ModelInterface::class))
            ->willReturn('MAIL_BODY');

        $service = new Composer($renderer->reveal());
        $message = $service->compose([], $template, new ViewModel());
        $this->assertEquals('MAIL_BODY', $message->getBody()->getPartContent(0));
    }

    public function testComposeRendersViewModelAndAssignsSubjectIfProvidedByViewModel()
    {
        $template = new HtmlTemplate();

        $renderer = $this->prophesize(RendererInterface::class);
        $renderer->render(Argument::type(ModelInterface::class))
            ->willReturn('MAIL_BODY');

        $service = new Composer($renderer->reveal());
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
        $renderer = $this->prophesize(RendererInterface::class);
        $renderer->render(Argument::type(ModelInterface::class))
            ->willReturn('MAIL_BODY');

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

        $service = new Composer($renderer->reveal());
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
        $renderer = $this->prophesize(RendererInterface::class);
        $renderer->render($replacement)
            ->willReturn('MAIL_BODY');

        $service = new Composer($renderer->reveal());
        $template = new HtmlTemplate();

        $service->getEventManager()->attach(ComposerEvent::EVENT_HTML_BODY_PRE, function ($event) use ($replacement) {
            $event->setViewModel($replacement);
        });

        $service->compose([], $template, new ViewModel());
        $this->assertTrue(true);
    }

    public function testTextTemplateHasCorrectCharset()
    {
        $viewModel = new ViewModel();
        $template = new TextTemplate();
        $renderer = $this->prophesize(RendererInterface::class);
        $renderer->render(Argument::type('Zend\View\Model\ViewModel'))->willReturn('BODY');
        $service = new Composer($renderer->reveal());

        $message = $service->compose([], $template, $viewModel);

        $parts = $message->getBody()->getParts();
        $textPart = $parts[0];
        $this->assertTrue(isset($textPart->charset));
        $this->assertSame($message->getHeaders()->getEncoding(), $textPart->charset);
    }
}
