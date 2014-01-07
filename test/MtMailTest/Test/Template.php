<?php

namespace MtMailTest\Test;

use MtMail\Template\HtmlTemplateInterface;
use MtMail\Template\TemplateInterface;
use Zend\View\Model\ViewModel;

class Template implements TemplateInterface, HtmlTemplateInterface
{

    /**
     * @return string
     */
    public function getHtmlTemplateName()
    {
        return 'template';
    }
}
