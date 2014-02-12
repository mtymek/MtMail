Mail Composer plugins
=====================

`MtMail` supports plugins that extend its basic functionality. During message creation
[`Composer`](Composing messages.md) service will trigger various events, allowing you to update message
body and headers.

Following events are triggered during message composition:

 * `compose.pre` - when "fresh" `Zend\Mail\Message` is created, allows to configure it
 * `headers.pre` - before headers are injected into message, allows injecting custom headers
 * `headers.post`- after headers are injected into message
 * `html_body.pre` - before HTML version of message is generated, allows to update `ViewModel`
 * `html_body.post` - after HTML version of message is generated
 * `text_body.pre` - before text version of message is generated, allows to update `ViewModel`
 * `text_body.post` - after text version of message is generated
 * `compose.post` - after message is generated


Plugins included by default
---------------------------

* [`DefaultHeaders`](#default-headers) - allows adding common headers to all your messages. Useful to set `From:` or `Reply-To:`.
* [`Layout`](#layout) - gives common HTML layout for all messages
* [`MessageEncoding`](#message-encoding) - applies specyfic encoding to message headers
* [`PlaintextMessage`](#plaintext-message) - automatically generates plaintext version of HTML message

### Default headers

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
    ),
);
```

`DefaultHeaders` is hooked to `headers.pre` event.

### Layout

A common requirement for e-mail system is a layout shared between all messages.
MtMail supports this feature via `Layout` plugin. You have to enable it in configuration, and specify layout
template to be used:

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

`layout.phtml` can be build just as your main application layout - at very minimum it has to echo `$this->content`
variable, containing main body of your message.

Here's an example of layout template that adds short footer:

```php
<?php echo $this->content ?>

<br /><br />
--<br />
Kind regards,<br />
Sales Team

```
Layout plugin only works for HTML templates.

`Layout` is hooked to `html_body.pre` event.

### Message Encoding

When working with localized e-mails, you'll usually need to indicate that message and headers (especially subject)
are encoded in UTF-8. Enable `MessageEncoding` plugin and configure it:

```php
return array(
    'mt_mail' => array(
        'composer_plugins' => array(
            'MessageEncoding',
        ),
        'message_encoding' => 'UTF-8',
    ),
);
```

`MessageEncoding` is hooked to `compose.pre` event.


Plaintext message
-----------------

`PlaintextMessage` plugin generates text version from HTML message, by replacing <BR> tags with newline characters,
and stripping remaining tags.

It doesn't require any configuration options - simply enable it in in application config:

```php
return array(
    'mt_mail' => array(
        'composer_plugins' => array(
            'PlaintextMessage',
        ),
    ),
);
```


Writing custom plugins
======================

Mail Composer plugin is a class implementing `MtMail\Plugin\PluginInterface`. It should attach listeners
to some event:

```php
class MyPlugin extends AbstractListenerAggregate implements PluginInterface
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(/* ... */);
    }
}
```

After plugin is defined, you need to tell plugin manager how to create it:

```php
return array(
    'mt_mail' => array(
        'renderer' => 'MtMail\Renderer\ZendView',
        'plugin_manager' => array(
            'invokables' => array(
                // use invokable...
                'MyPlugin' => 'MyModule\MailPlugin\MyPlugin',
            ),
            'factories' => array(
                // ...or factory
                'MyOtherPlugin' => 'MyModule\MailPlugin\MyOtherPlugin',
            ),
        ),
    ),
);
```

Finally, you can enable it:

```php
return array(
    'mt_mail' => array(
        'composer_plugins' => array(
            'MyPlugin'
        ),
    ),
);
```

For some examples please look at `src/MtMail/ComposerPlugin` directory.
