MtMail Services
===============

List of services registered by MtMail in application's ServiceManager:

* **`MtMail\Service\Composer`** composes e-mail messages from array of headers, template and variables
* **`MtMail\Service\ComposerPluginManager`** manages plugins used to manipulate message composition (default headers, layout...)
* **`MtMail\Service\Sender`** sends e-mail using configured transport
* **`MtMail\Service\SenderPluginManager`** manages plugins for sender service
* **`MtMail\Service\TemplateManager`** manager that can hold e-mail templates
* **`MtMail\Service\Mail`** service that wraps together `TemplateManager`, `Composer` and `Sender`, providing handy interface
