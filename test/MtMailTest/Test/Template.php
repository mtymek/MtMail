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

    /**
     * @return string
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * @return array
     */
    public function getDefaultHeaders()
    {
        return array();
    }
}