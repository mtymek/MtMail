<?php

namespace MtMailTest\Service;

use MtMail\Service\Mail as MailService;
use MtMailTest\Test\Template;
use Zend\Mail\Message;
use Zend\View\Model\ViewModel;

class MailTest extends \PHPUnit_Framework_TestCase
{
    public function testComposeRendersViewModelAndAssignsResultToMailBody()
    {
        $template = new Template();

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');

        $service = new MailService($renderer, $transport);
        $message = $service->compose($template);
        $this->assertEquals('MAIL_BODY', $message->getBody());
    }

    public function testComposeRendersViewModelAndAssignsSubjectIfProvidedByViewModel()
    {
        $template = new Template();
        $viewModel = new ViewModel(array(
            'subject' => 'MAIL_SUBJECT',
        ));

        $renderer = $this->getMock('MtMail\Renderer\RendererInterface', array('render'));
        $renderer->expects($this->once())->method('render')->with($this->isInstanceOf('Zend\View\Model\ModelInterface'))
            ->will($this->returnValue('MAIL_BODY'));

        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface');

        $service = new MailService($renderer, $transport);
        $message = $service->compose($template, $viewModel);
        $this->assertEquals('MAIL_BODY', $message->getBody());
        $this->assertEquals('MAIL_SUBJECT', $message->getSubject());
    }

    public function testSendPassesMessageToTransportObject()
    {
        $renderer = $this->getMock('MtMail\Renderer\RendererInterface');
        $message = new Message();
        $transport = $this->getMock('Zend\Mail\Transport\TransportInterface', array('send'));
        $transport->expects($this->once())->method('send')
            ->with($message);
        $service = new MailService($renderer, $transport);
        $service->send($message);
    }
}
