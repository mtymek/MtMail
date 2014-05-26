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
use MtMail\Template\TemplateInterface;
use MtMail\Template\TextTemplateInterface;

class TextTemplate implements TemplateInterface, TextTemplateInterface
{

    /**
     * @return string
     */
    public function getTextTemplateName()
    {
        return 'template';
    }
}
