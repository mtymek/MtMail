<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Plugin;

use MtMail\Event\ComposerEvent;
use MtMail\ComposerPlugin\MessageEncoding;
use PHPUnit\Framework\TestCase;
use Zend\Mail\Message;

class MessageEncodingTest extends TestCase
{
    /**
     * @var MessageEncoding
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new MessageEncoding();
    }

    public function testPluginSetsCorrectEncoding()
    {
        $message = $this->prophesize(Message::class);
        $message->setEncoding('UTF-8');
        $event = new ComposerEvent();
        $event->setMessage($message->reveal());
        $this->plugin->setEncoding('UTF-8');
        $this->plugin->setMessageEncoding($event);
        $this->assertEquals('UTF-8', $this->plugin->getEncoding());
    }

    public function testEncodingIsMutable()
    {
        $this->plugin->setEncoding('UTF-8');
        $this->assertEquals('UTF-8', $this->plugin->getEncoding());
    }
}
