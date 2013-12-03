<?php

namespace MtMail\Template;

/**
 * Default template used when developer wants to specify template file without
 * implementing TemplateInterface
 */
class SimpleHtml implements TemplateInterface, HtmlTemplateInterface
{
    /**
     * @var string
     */
    protected $htmlTemplateName;

    /**
     * Class constructor
     * @param string $htmlTemplateName
     */
    public function __construct($htmlTemplateName)
    {
        $this->htmlTemplateName = $htmlTemplateName;
    }

    /**
     * @return array
     */
    public function getDefaultHeaders()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getHtmlTemplateName()
    {
        return $this->htmlTemplateName;
    }

}