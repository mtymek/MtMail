<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\Service;

use MtMail\Exception\InvalidArgumentException;
use MtMail\Template\SimpleHtml;
use Zend\Mail\Message;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;

class Mail
{

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @var Sender
     */
    protected $sender;

    /**
     * @var TemplateManager
     */
    protected $templateManager;

    /**
     * Class constructor
     *
     * @param Composer        $composer
     * @param Sender          $sender
     * @param TemplateManager $templateManager
     */
    public function __construct(Composer $composer, Sender $sender, TemplateManager $templateManager)
    {
        $this->composer = $composer;
        $this->sender = $sender;
        $this->templateManager = $templateManager;
    }

    /**
     * Send e-mail - wrapper for Sender class
     *
     * @param Message $message
     */
    public function send(Message $message)
    {
        $this->sender->send($message);
    }

    /**
     * @param $template
     * @param  ModelInterface|array     $viewModel
     * @param  array                    $headers
     * @throws InvalidArgumentException
     * @return Message
     */
    public function compose(array $headers, $template, $viewModel = null)
    {
        if (is_array($viewModel)) {
            $viewModel = new ViewModel($viewModel);
        } elseif (null == $viewModel) {
            $viewModel = new ViewModel();
        }

        if (is_string($template)) {
            if ($this->templateManager->has($template)) {
                $template = $this->templateManager->get($template);
            } else {
                $template = new SimpleHtml($template);
            }
        }

        return $this->composer->compose($headers, $template, $viewModel);
    }
}
