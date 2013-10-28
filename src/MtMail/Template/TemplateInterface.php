<?php

namespace MtMail\Template;

use Zend\View\Model\ViewModel;

interface TemplateInterface
{
    /**
     * @return ViewModel
     */
    public function getDefaultViewModel();
}