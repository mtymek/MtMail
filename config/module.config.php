<?php

return array(
    'mt_mail' => array(
        'renderer' => 'MtMail\Renderer\ZendView',
        'plugin_manager' => array(
            'invokables' => array(
                'PlaintextMessage' => 'MtMail\Plugin\PlaintextMessage',
            ),
            'factories' => array(
                'Layout' => 'MtMail\Factory\LayoutPluginFactory',
                'DefaultHeaders' => 'MtMail\Factory\DefaultHeadersPluginFactory',
                'MessageEncoding' => 'MtMail\Factory\MessageEncodingPluginFactory',
            ),
        ),
        'plugins' => array(
            'DefaultHeaders'
        ),
        'default_headers' => array(),
    ),
    'service_manager' => array(
        'invokables' => array(
            'Zend\Mail\Transport\Sendmail' => 'Zend\Mail\Transport\Sendmail',
        ),
        'factories' => array(
            'MtMail\Service\Composer' => 'MtMail\Factory\ComposerServiceFactory',
            'MtMail\Service\Sender' => 'MtMail\Factory\SenderServiceFactory',
            'MtMail\Service\Mail' => 'MtMail\Factory\MailServiceFactory',
            'MtMail\Plugin\Manager' => 'MtMail\Factory\PluginManagerFactory',
            'MtMail\Renderer\ZendView' => 'MtMail\Factory\ZendViewRendererFactory',
            'Zend\Mail\Transport\Smtp' => 'MtMail\Factory\SmtpTransportFactory',
            'Zend\Mail\Transport\File' => 'MtMail\Factory\FileTransportFactory',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'MtMail' => 'MtMail\Factory\MtMailPlugin',
        )
    ),
);
