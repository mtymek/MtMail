E-mail module for Zend Framework 2
==================================

It's a fork of mtymek/mt-mail package

Introduction
------------
MtMail handles common activities surrounding sending e-mail from applications, mainly creating messages
from templates, and sending them through transport adapters.

### Features:
* factory for creating e-mail messages
* factory for e-mail transport adapters, service for one-line dispatch
* rendering templates from `phtml` files, using `Zend\View` and `PhpRenderer`
* rendering templates with layouts
* plugins for various common tasks: from setting default headers to generating plaintext version of HTML message
* plugin support via dedicated plugin managers
* plugin for integrate image in email (EmbeddingImages)
* new service to add attachments to your email

Installation
------------
Installation is supported via Composer:

1. Add `"mtymek/mt-mail":"1.1.*"` to your `composer.json` file and run `php composer.phar update`.
2. Add MtMail to your `config/application.config.php` file under the modules key.


Creating e-mails
----------------

### Configuration

By default MtMail doesn't require any extra configuration. By default it will use `Zend\View` to render
templates accessible by your application.

### Usage

Creating message from controller:

```php
$mailService = $this->getServiceLocator()->get('MtMail\Service\Mail');

$headers = array(
    'to' => 'johndoe@domain.com, Jade DOE<jade.doe@domain.net>',
    'from' => 'contact@mywebsite.com',
);
$variables = array(
    'userName' => 'John Doe',
);
$message = $mailService->compose($headers, 'application/mail/welcome.phtml', $variables);
/** @var \Zend\Mail\Message $message */
```

This snippet will create a message, compose it with `$headers` and HTML body
rendered from `welcome.phtml` template (injected with `$variables` array).


### Layouts

In order to give your e-mails common layout, you have to enable "Layout" plugin and tell it where
to look for layout template:

```php
return array(
    'mt_mail' => array(
        'composer_plugins' => array(
            'Layout',
        ),
        'layout' => 'application/mail/layout.phtml',
    ),
);
```

For more info about composing e-mail messages, check [the documentation](doc/Composing messages.md).
You can also check [documentation for plugins](doc/Composer Plugins.md).

Sending e-mails
---------------

### Configuration

Update your application config:

```php
return array(
    'mt_mail' => array(
        'transport' => 'Zend\Mail\Transport\Smtp',
        'transport_options' => array(
            'host' => 'some-host.com',
            'connection_class' => 'login',
            'connection_config' => array(
                'username' => 'user',
                'password' => 'pass',
                'ssl' => 'tls',
            ),
        ),
    ),
),
```
### Usage
---------

Add following code to your controller:

```php
// create and configure message
$message = new Message();
$message->addTo('johndoe@domain.com');
// ...

// send!
$mailService = $this->getServiceLocator()->get('MtMail\Service\Mail');
$mailService->send($message);
```

For more info on sending e-mails, check [the documentation](doc/Sending messages.md).
