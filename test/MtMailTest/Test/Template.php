<?php

namespace MtMailTest\Test;


use MtMail\Template\TemplateInterface;
use Zend\View\Model\ViewModel;

class Template implements TemplateInterface
{

    /**
     * @return ViewModel
     */
    public function getDefaultViewModel()
    {
        $vm = new ViewModel();
        return $vm;
    }
}