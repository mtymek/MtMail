<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

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
