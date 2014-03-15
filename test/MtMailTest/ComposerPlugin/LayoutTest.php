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
use MtMail\ComposerPlugin\Layout;
use MtMail\Service\Composer;
use MtMailTest\Test\LayoutProviderTemplate;
use Zend\View\Model\ViewModel;

class LayoutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Layout
     */
    protected $plugin;

    public function setUp()
    {
        $this->plugin = new Layout();
    }

    public function testLayoutTemplateIsMutable()
    {
        $this->plugin->setLayoutTemplate('layout.phtml');
        $this->assertEquals('layout.phtml', $this->plugin->getLayoutTemplate());
    }

    public function testInjectLayoutViewModelCreatesParentForExistingViewModel()
    {
        $viewModel = new ViewModel();
        $event = new ComposerEvent();
        $event->setViewModel($viewModel);
        $this->plugin->injectLayoutViewModel($event);
        $this->assertEquals(array($viewModel), $event->getViewModel()->getChildren());
    }

    public function testInjectLayoutViewModelDoesNotCreateParentIfModelTerminates()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $event = new ComposerEvent();
        $event->setViewModel($viewModel);
        $this->plugin->injectLayoutViewModel($event);
        $this->assertEquals(array(), $event->getViewModel()->getChildren());
        $this->assertEquals($viewModel, $event->getViewModel());
    }

    public function testPluginCanInjectTemplateSpecyficLayout()
    {
        $template = new LayoutProviderTemplate;
        $viewModel = new ViewModel();

        $event = new ComposerEvent();
        $event->setTemplate($template);
        $event->setViewModel($viewModel);

        $this->plugin->injectLayoutViewModel($event);
        $this->assertEquals($template->getLayout(), $event->getViewModel()->getTemplate());
    }

}
