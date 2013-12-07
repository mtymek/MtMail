<?php

namespace MtMail\Renderer;


use Zend\View\Model\ModelInterface;
use Zend\View\View;
use Zend\View\ViewEvent;

/**
 * Re-use Zend\View in order to render template with children
 */
class ZendView extends View implements RendererInterface
{
    /**
     * @var View
     */
    protected $view;

    /**
     * Class constructor
     *
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param ModelInterface $model
     * @return string
     */
    public function render(ModelInterface $model)
    {
        // quirk - setting has_parent to true will force
        // View::render to return output
        $model->setOption('has_parent', true);
        return $this->view->render($model);
    }


}
