<?php

namespace MtMail\Template;


interface HtmlTemplateInterface
{
    /**
     * @return string
     */
    public function getHtmlTemplateName();


    /**
     * @return array
     */
    public function getDefaultHeaders();

}