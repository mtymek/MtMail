<?php

namespace MtMail\Template;


interface HtmlTemplateInterface extends TemplateInterface
{
    /**
     * @return string
     */
    public function getHtmlTemplateName();

}
