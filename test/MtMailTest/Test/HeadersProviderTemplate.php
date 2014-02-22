<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

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
