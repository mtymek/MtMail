<?php

namespace MtMailTest\Test;

use MtMail\Template\HtmlTemplateInterface;
use MtMail\Template\LayoutProviderInterface;
use MtMail\Template\TemplateInterface;
use Zend\View\Model\ViewModel;

class LayoutProviderTemplate implements HtmlTemplateInterface, LayoutProviderInterface
{

    /**
     * @return string
     */
    public function getHtmlTemplateName()
    {
        return 'template';
    }

    public function getLayout()
    {
        return 'specific-layout.phtml';
    }
}
