<?php

return array(
    'mt_mail' => array(
        'renderer' => 'MtMail\Renderer\ZendView',
        'plugin_manager' => array(
            'factories' => array(
                'Layout' => 'MtMail\Factory\LayoutPluginFactory',
                'DefaultHeaders' => 'MtMail\Factory\DefaultHeadersPluginFactory',
            ),
        ),
        'plugins' => array(

        ),
        'layout' => 'mail/layout.phtml',
    ),
    'service_manager' => array(
        'invokables' => array(
            'Zend\Mail\Transport\Sendmail' => 'Zend\Mail\Transport\Sendmail',
        ),
        'factories' => array(
            'MtMail\Service\MailComposer' => 'MtMail\Factory\MailComposerFactory',
            'MtMail\Service\MailSender' => 'MtMail\Factory\MailSenderFactory',
            'MtMail\Service\Mail' => 'MtMail\Factory\MailServiceFactory',
            'MtMail\Plugin\Manager' => 'MtMail\Factory\PluginManagerFactory',
            'MtMail\Renderer\ZendView' => 'MtMail\Factory\ZendViewRendererFactory',
            'Zend\Mail\Transport\Smtp' => 'MtMail\Factory\SmtpTransportFactory',
            'Zend\Mail\Transport\File' => 'MtMail\Factory\FileTransportFactory',
        ),
    ),
);