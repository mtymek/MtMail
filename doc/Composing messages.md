E-mail Composer
===============

The Basics
----------

If you just want to create simple e-mail message, you don't need to configure anything
- MtMail will work out of the box with basic settings, utilizing application's `View`
for rendering templates. Here's minimal code required to create a message:

```php
$mailService = $this->getServiceLocator()->get('MtMail\Service\Mail');

$variables = array(
    'userName' => 'John Doe',
);
$headers = array(
    'to' => 'johndoe@domain.com',
    'from' => 'contact@mywebsite.com',
);
$message = $mailService->compose('application/mail/welcome.phtml', $variables, $headers);
/** @var \Zend\Mail\Message $message */
```

Default headers
---------------

Usually you will want to send all your e-mails with common `From` and `Reply-To` headers. This can
be achieved using `DefaultHeaders` plugin. It is enabled by default, so only thing you need to do
is to add headers of your choice to application config:

```php
return array(
    'mt_mail' => array(
        'default_headers' => array(
            'from' => 'My Website <information-no-reply@mywebsite.com>',
            'reply-to' => 'Contact <contact@mywebsite.com>',
        ),
);
```

Layout
------

An often requirement for all application's outgoing mails is common layout shared between all messages.
MtMail supports this feature via `Zend\Mail` and `Layout` plugin. You have to enable it in configuration,
and specify layout template to be used:

```php
return array(
    'mt_mail' => array(
        'plugins' => array(
            'Layout',
        ),
        'layout' => 'application/mail/layout.phtml',
);
```

`layout.phtml` can be build just as your main application layout - at very minimum it has to echo `$this->content`
variable, containing main body of your message.

Here's an example of layout template that adds short footer:

```php
<?php echo $this->content ?>

--
Kind regards,
Our great sales team

```


Events
------

Composing e-mail message is a process with following steps:

1. Create `Zend\Mail\Message` object
2. Inject message with headers
3. Render HTML body
4. Render text body
5. Inject body into message

Following events are triggered during message composition:

* `compose.pre`
* `headers.pre`
* `headers.post`
* `html_body.pre`
* `html_body.post`
* `text_body.pre`
* `text_body.post`
* `compose.post`