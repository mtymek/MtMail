<?php

return array(
    'mt_mail' => array(
        'plugin_manager' => array(
            'factories' => array(
                'Layout' => 'MtMail\Factory\LayoutPluginFactory',
            ),
        ),
        'plugins' => array(

        ),
        'layout_plugin' => array(
            'layout' => 'mail/layout.phtml'
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'MtMail\Service\MailComposer' => 'MtMail\Factory\MailComposerFactory',
            'MtMail\Service\MailSender' => 'MtMail\Factory\MailSenderFactory',
            'MtMail\Plugin\Manager' => 'MtMail\Factory\PluginManagerFactory',
            'MtMail\Renderer\Zend' => 'App\Factory\ZendRendererFactory',
            'Zend\Mail\Transport\Smtp' => 'MtMail\Factory\SmtpTransportFactory',
            'Zend\Mail\Transport\File' => 'MtMail\Factory\FileTransportFactory',
        ),
    ),
);