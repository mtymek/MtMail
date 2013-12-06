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
$sender = $this->getServiceLocator()->get('MtMail\Service\Mail');
$sender->send($message);
```

For more info on sending e-mails, check [the documentation](doc/Sending messages.md).