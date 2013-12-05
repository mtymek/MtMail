<?php

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