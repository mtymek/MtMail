<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Test;

use MtMail\Template\HtmlTemplateInterface;
use MtMail\Template\LayoutProviderInterface;

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
