<?php

/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

return array(
    'mt_mail' => array(
        'renderer' => 'MtMail\Renderer\ZendView',
        'composer_plugin_manager' => array(
            'invokables' => array(
                'PlaintextMessage' => 'MtMail\ComposerPlugin\PlaintextMessage',
            ),
            'factories' => array(
                'Layout'          => 'MtMail\Factory\LayoutPluginFactory',
                'DefaultHeaders'  => 'MtMail\Factory\DefaultHeadersPluginFactory',
                'MessageEncoding' => 'MtMail\Factory\MessageEncodingPluginFactory',
            ),
        ),
        'composer_plugins' => array(
            'DefaultHeaders'
        ),
        'default_headers' => array(),
        'transport' => 'Zend\Mail\Transport\Sendmail',
    ),
    'service_manager' => array(
        'invokables' => array(
            'Zend\Mail\Transport\Sendmail' => 'Zend\Mail\Transport\Sendmail',
        ),
        'factories' => array(
            'MtMail\Renderer\ZendView'             => 'MtMail\Factory\ZendViewRendererFactory',
            'MtMail\Service\Composer'              => 'MtMail\Factory\ComposerServiceFactory',
            'MtMail\Service\Sender'                => 'MtMail\Factory\SenderServiceFactory',
            'MtMail\Service\Mail'                  => 'MtMail\Factory\MailServiceFactory',
            'MtMail\Service\ComposerPluginManager' => 'MtMail\Factory\ComposerPluginManagerFactory',
            'MtMail\Service\SenderPluginManager'   => 'MtMail\Factory\SenderPluginManagerFactory',
            'MtMail\Service\TemplateManager'       => 'MtMail\Factory\TemplateManagerFactory',
            'Zend\Mail\Transport\Smtp'             => 'MtMail\Factory\SmtpTransportFactory',
            'Zend\Mail\Transport\File'             => 'MtMail\Factory\FileTransportFactory',
        ),
    ),
    'controller_plugins' => array(
        'factories' => array(
            'MtMail' => 'MtMail\Factory\MtMailPlugin',
        )
    ),
);
