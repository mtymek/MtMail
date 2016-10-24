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
                'embeddingimages'  => EmbeddingImages::class,
                'embeddingImages'  => EmbeddingImages::class,
                'EmbeddingImages'  => EmbeddingImages::class,
            ],
            'factories' => [
                PlaintextMessage::class => InvokableFactory::class,
                Layout::class           => LayoutPluginFactory::class,
                DefaultHeaders::class   => DefaultHeadersPluginFactory::class,
                MessageEncoding::class  => MessageEncodingPluginFactory::class,
                EmbeddingImages::class  => InvokableFactory::class,
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
            'MtMail\Renderer\ZendView'             => 'MtMail\Factory\ZendViewRendererFactory',
            'MtMail\Service\Composer'              => 'MtMail\Factory\ComposerServiceFactory',
            'MtMail\Service\Sender'                => 'MtMail\Factory\SenderServiceFactory',
            'MtMail\Service\Mail'                  => 'MtMail\Factory\MailServiceFactory',
            'MtMail\Service\ComposerPluginManager' => 'MtMail\Factory\ComposerPluginManagerFactory',
            'MtMail\Service\SenderPluginManager'   => 'MtMail\Factory\SenderPluginManagerFactory',
            'MtMail\Service\TemplateManager'       => 'MtMail\Factory\TemplateManagerFactory',
            'Zend\Mail\Transport\Smtp'             => 'MtMail\Factory\SmtpTransportFactory',
            'Zend\Mail\Transport\File'             => 'MtMail\Factory\FileTransportFactory',
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            'MtMail' => 'MtMail\Factory\MtMailPlugin',
        ]
    ],
];
