Template Manager
================

As your application grows, you may want to better organize e-mail templates in object-oriented manner.

What are the benefits?

* you have single place (template manager configuration) that lists all available templates
* you get access to advanced configuration options: default template headers and customized layout
* unit testing is easier: you can check that tested service returns given template by using "instance of" assertion

Available interfaces
--------------------

* **HtmlTemplateInterface** - template provides view script to be rendered as `text/html` message part
* **TextTemplateInterface** - template provides view script to be rendered as `text/plain` message part
* **LayoutProviderInterface** - template has a separate layout (see example below)
* **HeadersProviderInterface** - template provides specific headers (`Subject:`)

MtMail uses templates internally. When passing view script name, as in this example:

```php
$message = $mailService->compose($headers, 'application/mail/welcome.phtml');
```

...Mail service will create and instance of `MtMail\Template\SimpleHtml` class, which implements `HtmlTemplateInterface`.

Example - multiple layouts
--------------------------

Start with simple layout configuration:

```php
return array(
    'mt_mail' => array(
        'composer_plugins' => array(
            'Layout'
        ),
        'layout' => 'mail/layout.phtml',
    ),
);
```

Create your template:

```php
namespace FooModule\EmailTemplate;

use MtMail\Template\HtmlTemplateInterface;
use MtMail\Template\LayoutProviderInterface;

class FooBarTemplate implements HtmlTemplateInterface, LayoutProviderInterface
{

    /**
     * @return string
     */
    public function getHtmlTemplateName()
    {
        return 'mail/test.phtml';
    }

    public function getLayout()
    {
        return 'mail/another-layout.phtml';
    }
}
```

Configure template manager:

```php
return array(
    'mt_mail' => array(
        'template_manager' => array(
            'invokables' => array(
                'FooBarTemplate' => 'FooModule\EmailTemplate\FooBarTemplate'
            ),
        ),
    ),
);
```

Finally, usage:

```php
$headers = array(...);
$mail = $this->mtMail()->compose($headers, 'FooBarTemplate');
```
