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
use MtMail\ComposerPlugin\MessageEncoding;

class MessageEncodingTest extends \PHPUnit_Framework_TestCase
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
        $message = $this->getMock('Zend\Mail\Message', array('setEncoding'));
        $message->expects($this->once())->method('setEncoding')->with('UTF-8');
        $event = new ComposerEvent();
        $event->setMessage($message);
        $this->plugin->setEncoding('UTF-8');
        $this->plugin->setMessageEncoding($event);
    }

    public function testEncodingIsMutable()
    {
        $this->plugin->setEncoding('UTF-8');
        $this->assertEquals('UTF-8', $this->plugin->getEncoding());
    }

}
