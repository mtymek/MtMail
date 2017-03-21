<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Plugin;

use MtMail\Event\ComposerEvent;
use MtMail\ComposerPlugin\DefaultHeaders;
use MtMailTest\Test\HeaderObjectProviderTemplate;
use MtMailTest\Test\HeadersProviderTemplate;
use PHPUnit\Framework\TestCase;
use Zend\Mail\Headers;
use Zend\Mail\Header\Subject;
use Zend\Mail\Message;

class DefaultHeadersTest extends TestCase
{
    /**
     * @var DefaultHeaders
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new DefaultHeaders();
    }

    public function testLayoutHeadersIsMutable()
    {
        $this->plugin->setHeaders(['from' => 'sender@domain.tld']);
        $this->assertEquals(['from' => 'sender@domain.tld'], $this->plugin->getHeaders());
    }

    public function testInjectDefaultHeadersSetsHeaders()
    {
        $this->plugin->setHeaders([
                'from' => 'sender@domain.com',
                'subject' => 'Hello!',
            ]);
        $headers = $this->prophesize(Headers::class);
        $headers->setEncoding("ASCII")->shouldBeCalled();
        $headers->addHeaderLine('from', 'sender@domain.com')->shouldBeCalled();
        $headers->addHeaderLine('subject', 'Hello!')->shouldBeCalled();
        $message = new Message();
        $message->setHeaders($headers->reveal());
        $event = new ComposerEvent();
        $event->setMessage($message);
        $this->plugin->injectDefaultHeaders($event);
    }

    public function testPluginCanInjectTemplateSpecificHeaders()
    {
        $headers = $this->prophesize(Headers::class);
        $headers->setEncoding("ASCII")->shouldBeCalled();
        $headers->addHeaderLine('subject', 'Default subject')->shouldBeCalled();
        $template = new HeadersProviderTemplate();
        $message = new Message();
        $message->setHeaders($headers->reveal());
        $event = new ComposerEvent();
        $event->setMessage($message);
        $event->setTemplate($template);
        $this->plugin->injectDefaultHeaders($event);
    }

    public function testPluginCanInjectHeaderObjects()
    {
        $subject = (new Subject())->setSubject('Hello!');
        $this->plugin->setHeaders([
            'subject' => $subject,
        ]);

        $headers = $this->prophesize(Headers::class);
        $headers->addHeader($subject);
        $message = new Message();
        $message->setHeaders($headers->reveal());
        $event = new ComposerEvent();
        $event->setMessage($message);
        $this->plugin->injectDefaultHeaders($event);
    }

    public function testPluginCanInjectTemplateSpecificHeaderObjects()
    {
        $headers = $this->prophesize(Headers::class);
        $headers->addHeader((new Subject())->setSubject('Default subject'));
        $template = new HeaderObjectProviderTemplate();
        $message = new Message();
        $message->setHeaders($headers->reveal());
        $event = new ComposerEvent();
        $event->setMessage($message);
        $event->setTemplate($template);
        $this->plugin->injectDefaultHeaders($event);
    }
}
