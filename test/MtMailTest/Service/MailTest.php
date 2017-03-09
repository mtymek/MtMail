<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Service;

use MtMail\Service\Composer;
use MtMail\Service\Mail;
use MtMail\Service\Sender;
use MtMail\Service\TemplateManager;
use MtMail\Template\SimpleHtml;
use MtMail\Template\TemplateInterface;
use Prophecy\Argument;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;

class MailTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Mail
     */
    protected $service;

    public function testSendProxiesToSender()
    {
        $message = new Message();
        $sender = $this->prophesize(Sender::class);
        $sender->send($message)->shouldBeCalled();

        $composer = $this->prophesize(Composer::class);
        $templateManager = $this->prophesize(TemplateManager::class);
        $service = new Mail($composer->reveal(), $sender->reveal(), $templateManager->reveal());
        $service->send($message);
    }

    public function testComposeProxiesToComposer()
    {
        $sender = $this->prophesize(Sender::class);
        $template = $this->prophesize(TemplateInterface::class);
        $composer = $this->prophesize(Composer::class);
        $composer->compose(
            ['to' => 'johndoe@domain.com'],
            $template,
            Argument::type(ModelInterface::class)
        )->shouldBeCalled();
        $templateManager = $this->prophesize(TemplateManager::class);
        $service = new Mail($composer->reveal(), $sender->reveal(), $templateManager->reveal());
        $service->compose(['to' => 'johndoe@domain.com'], $template->reveal());
    }

    public function testComposeTriesPullsTemplateFromManager()
    {
        $sender = $this->prophesize(Sender::class);
        $composer = $this->prophesize(Composer::class);
        $templateManager = $this->prophesize(TemplateManager::class);
        $templateManager->has('FooTemplate')->willReturn(true);
        $templateManager->get('FooTemplate')->willReturn($this->prophesize(TemplateInterface::class)->reveal());
        $service = new Mail($composer->reveal(), $sender->reveal(), $templateManager->reveal());
        $service->compose(['to' => 'johndoe@domain.com'], 'FooTemplate');
        $this->assertTrue(true);
    }

    public function testComposeFallsBackToDefaultHtmlTemplate()
    {
        $sender = $this->prophesize(Sender::class);
        $composer = $this->prophesize(Composer::class);
        $composer->compose(
            ['to' => 'johndoe@domain.com'],
            Argument::type(SimpleHtml::class),
            Argument::type(ModelInterface::class)
        );
        $templateManager = $this->prophesize(TemplateManager::class);
        $templateManager->has('FooTemplate')->willReturn(false);
        $service = new Mail($composer->reveal(), $sender->reveal(), $templateManager->reveal());
        $service->compose(['to' => 'johndoe@domain.com'], 'FooTemplate', []);
        $this->assertTrue(true);
    }
}
