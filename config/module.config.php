<?php

/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

use MtMail\ComposerPlugin\DefaultHeaders;
use MtMail\ComposerPlugin\Layout;
use MtMail\ComposerPlugin\MessageEncoding;
use MtMail\ComposerPlugin\PlaintextMessage;
use MtMail\Factory\DefaultHeadersPluginFactory;
use MtMail\Factory\LayoutPluginFactory;
use MtMail\Factory\MessageEncodingPluginFactory;
use MtMail\Renderer\ZendView;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'mt_mail' => [
        'renderer' => ZendView::class,
        'composer_plugin_manager' => [
            'aliases' => [
                'PlaintextMessage' => PlaintextMessage::class,
                'plaintextMessage' => PlaintextMessage::class,
                'plaintextmessage' => PlaintextMessage::class,
                'Layout'           => Layout::class,
                'layout'           => Layout::class,
                'DefaultHeaders'   => DefaultHeaders::class,
                'defaultHeaders'   => DefaultHeaders::class,
                'defaultheaders'   => DefaultHeaders::class,
                'MessageEncoding'  => MessageEncoding::class,
                'messageEncoding'  => MessageEncoding::class,
                'messageencoding'  => MessageEncoding::class,
            ],
            'factories' => [
                PlaintextMessage::class => InvokableFactory::class,
                Layout::class           => LayoutPluginFactory::class,
                DefaultHeaders::class   => DefaultHeadersPluginFactory::class,
                MessageEncoding::class  => MessageEncodingPluginFactory::class,
            ],
        ],
        'composer_plugins' => [
            'DefaultHeaders'
        ],
        'default_headers' => [],
        'transport' => Zend\Mail\Transport\Sendmail::class,
    ],
    'service_manager' => [
        'invokables' => [
            Zend\Mail\Transport\Sendmail::class => Zend\Mail\Transport\Sendmail::class,
        ],
        'factories' => [
            MtMail\Renderer\ZendView::class             => MtMail\Factory\ZendViewRendererFactory::class,
            MtMail\Service\Composer::class              => MtMail\Factory\ComposerServiceFactory::class,
            MtMail\Service\Sender::class                => MtMail\Factory\SenderServiceFactory::class,
            MtMail\Service\Mail::class                  => MtMail\Factory\MailServiceFactory::class,
            MtMail\Service\ComposerPluginManager::class => MtMail\Factory\ComposerPluginManagerFactory::class,
            MtMail\Service\SenderPluginManager::class   => MtMail\Factory\SenderPluginManagerFactory::class,
            MtMail\Service\TemplateManager::class       => MtMail\Factory\TemplateManagerFactory::class,
            Zend\Mail\Transport\Smtp::class             => MtMail\Factory\SmtpTransportFactory::class,
            Zend\Mail\Transport\File::class             => MtMail\Factory\FileTransportFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'MtMail' => MtMail\Factory\MtMailPlugin::class,
        ]
    ],
];
