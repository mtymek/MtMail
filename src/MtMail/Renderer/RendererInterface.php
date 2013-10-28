<?php

namespace MtMail\Renderer;

use Zend\View\Model\ModelInterface;

interface RendererInterface
{
    /**
     * @param ModelInterface $viewModel
     * @return string
     */
    public function render(ModelInterface $viewModel);
}
