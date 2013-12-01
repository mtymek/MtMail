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


Configuration
-------------

### E-mail transport

Before sending an e-mail, you need to tell MtMail which transport to use, and (optionally) configure it.
MtMail provides factories for Zend Framework's built-in transport classes, allowing you to pass options
using application configuration:

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

`transport` can be any service that is accessible from `ServiceManager` and implements `Zend\Mail\Transport\TransportInterface`.
You can use this if you want to benefit from non-standard transports (for instance those from [SlmMail](https://github.com/juriansluiman/SlmMail) module).


Sending e-mails
---------------

Once e-mail transport is configured, `MtMail\Service\MailSender` becomes ready to be pulled from `ServiceManager`.
Usage:


```php
// inside controller

// configure message
$message = new Message();
$message->addTo('johndoe@domain.com');
// ...

// send!
$sender = $this->getServiceLocator()->get('MtMail\Service\MailSender');
$sender->send($message);
```