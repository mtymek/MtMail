E-mail Composer
===============

Composing e-mail message is a process with following steps:

1. Create `Zend\Mail\Message` object
2. Inject message with headers
3. Render HTML body
4. Render text body
5. Inject body into message


Events
------

Following events are triggered during message composition:

* `compose.pre`
* `headers.pre`
* `headers.post`
* `html_body.pre`
* `html_body.post`
* `text_body.pre`
* `text_body.post`
* `compose.post`