<?php

namespace MtMail\Template;

use Zend\View\Model\ViewModel;

/**
 * Default template used when developer wants to specify template file without
 * implementing TemplateInterface
 */
class Simple implements TemplateInterface
{
    /**
     * @var ViewModel
     */
    protected $viewModel;

    /**
     * Class constructor
     * @param string $template
     */
    public function __construct($template)
    {
        $this->viewModel = new ViewModel();
        $this->viewModel->setTemplate($template);
    }

    /**
     * @return ViewModel
     */
    public function getDefaultViewModel()
    {
        return $this->viewModel;
    }

    /**
     * @return array
     */
    public function getDefaultHeaders()
    {
        return array();
    }
}