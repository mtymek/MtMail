<?php

namespace MtMailTest\Test;

use MtMail\Template\HeadersProviderInterface;
use MtMail\Template\HtmlTemplateInterface;

class HeadersProviderTemplate implements HtmlTemplateInterface, HeadersProviderInterface
{

    /**
     * @return string
     */
    public function getHtmlTemplateName()
    {
        return 'template';
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return array(
            'subject' => 'Default subject'
        );
    }
}
