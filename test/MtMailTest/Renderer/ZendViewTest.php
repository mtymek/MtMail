<?php
/**
 * MtMail - e-mail module for Zend Framework
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2017 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Renderer;

use MtMail\Renderer\ZendView;
use PHPUnit\Framework\TestCase;
use Zend\View\Model\ViewModel;

class ZendViewTest extends TestCase
{
    public function testRenderSetsViewModelAndCallsZendViewRender()
    {
        $viewModel = $this->prophesize(ViewModel::class);
        $viewModel->setOption('has_parent', true)->shouldBeCalled();

        $view = $this->prophesize(\Zend\View\View::class);
        $view->render($viewModel->reveal());

        $renderer = new ZendView($view->reveal());
        $renderer->render($viewModel->reveal());
    }
}
