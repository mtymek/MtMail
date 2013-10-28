<?php

namespace MtMail\Renderer;


use MtMail\Template\TemplateInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Renderer\RendererInterface as ZendRendererInterface;

class Zend implements RendererInterface
{
    /**
     * @var ZendRendererInterface
     */
    protected $renderer;

    /**
     * @param ZendRendererInterface $renderer
     */
    public function __construct(ZendRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * @param ModelInterface $viewModel
     * @return string
     */
    public function render(ModelInterface $viewModel)
    {
        return $this->renderer->render($viewModel);
    }
}