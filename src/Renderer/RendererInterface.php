<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Renderer;

use Zend\View\Model\ModelInterface;

interface RendererInterface
{
    /**
     * @param  ModelInterface $viewModel
     * @return string
     */
    public function render(ModelInterface $viewModel);
}
