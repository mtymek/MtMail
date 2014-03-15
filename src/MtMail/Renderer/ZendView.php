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
use Zend\View\View;

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
     * @param  ModelInterface $model
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
