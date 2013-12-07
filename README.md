E-mail module for Zend Framework 2
==================================

[![Build Status](https://travis-ci.org/mtymek/MtMail.png?branch=master)](https://travis-ci.org/mtymek/MtMail)

Introduction
------------
MtMail handles common activities surrounding sending e-mail from applications, mainly creating messages
from templates, and sending them through transport adapters.

### Features:
* factory for creating e-mail messages
* factory for e-mail transport adapters, service for one-line dispatch
* rendering templates from `phtml` files, using `Zend\View` and `PhpRenderer`
* rendering templates with layouts
* plugin support via dedicated plugin manager

Installation
------------
Installation is supported via Composer:

1. Add `"mtymek/mt-mail":"dev-master"` to your `composer.json` file and run `php composer.phar update`.
2. Add MtMail to your `config/application.config.php` file under the modules key.


Creating e-mails
----------------

### Configuration

By default MtMail doesn't require any extra configuration. By default it will use `Zend\View` to render
templates accessible by your application.

### Usage

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

This snippet will create a message, compose it with `$headers` and HTML body
rendered from `welcome.phtml` template (injected with `$variables` array).

For more info about composing e-mail messages, check [the documentation](doc/Composing messages.md).


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