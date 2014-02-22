<?php

namespace MtMail\Template;

/**
 * Default template used when developer wants to specify template file without
 * implementing TemplateInterface
 */
class SimpleHtml implements HtmlTemplateInterface
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
     * @return string
     */
    public function getHtmlTemplateName()
    {
        return $this->htmlTemplateName;
    }

}
