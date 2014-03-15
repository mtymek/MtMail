<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Renderer;

use MtMail\Renderer\ZendView;
use PHPUnit_Framework_TestCase;

class ZendViewTest extends PHPUnit_Framework_TestCase
{
    public function testRenderSetsViewModelAndCallsZendViewRender()
    {
        $viewModel = $this->getMock('Zend\View\Model\ViewModel', array('setOption'));
        $viewModel->expects($this->once())->method('setOption')
            ->with('has_parent', true);

        $view = $this->getMock('Zend\View\View', array('render'));
        $view->expects($this->once())->method('render')
            ->with($viewModel);

        $renderer = new ZendView($view);
        $renderer->render($viewModel);
    }
}
