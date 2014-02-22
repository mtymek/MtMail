<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMailTest\Template;

use MtMail\Template\SimpleHtml;

class SimpleHtmlTest extends \PHPUnit_Framework_TestCase
{

    public function testGetHtmlTemplateReturnsTemplatePassedToConstructor()
    {
        $simpleHtml = new SimpleHtml('template.phtml');
        $this->assertEquals('template.phtml', $simpleHtml->getHtmlTemplateName());
    }

}
