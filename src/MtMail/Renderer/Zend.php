<?php

namespace MtMail\Renderer;


use Zend\View\Model\ModelInterface;
use Zend\View\View;
use Zend\View\ViewEvent;

/**
 * Re-use Zend\View in order to render template with children
 */
class Zend extends View implements RendererInterface
{

    public function render(ModelInterface $model)
    {
        // quirk - setting has_parent to true will force
        // View::render to return
        $model->setOption('has_parent', true);
        return parent::render($model);
    }


}