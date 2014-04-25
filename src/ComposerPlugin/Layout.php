<?php
/**
 * MtMail - e-mail module for Zend Framework 2
 *
 * @link      http://github.com/mtymek/MtMail
 * @copyright Copyright (c) 2013-2014 Mateusz Tymek
 * @license   BSD 2-Clause
 */

namespace MtMail\ComposerPlugin;

use MtMail\Event\ComposerEvent;
use MtMail\Template\LayoutProviderInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\View\Model\ViewModel;

class Layout extends AbstractListenerAggregate implements PluginInterface
{

    /**
     * @var string
     */
    protected $layoutTemplate = 'mail/layout.phtml';

    /**
     * @param ComposerEvent $event
     */
    public function injectLayoutViewModel(ComposerEvent $event)
    {
        $currentViewModel = $event->getViewModel();
        // don't render layout if ViewModel says so
        if ($currentViewModel->terminate()) {
            return;
        }
        $layoutModel = new ViewModel();
        $layoutModel->addChild($currentViewModel);

        if ($event->getTemplate() instanceof LayoutProviderInterface) {
            $layout = $event->getTemplate()->getLayout();
        } else {
            $layout = $this->layoutTemplate;
        }

        $layoutModel->setTemplate($layout);
        $event->setViewModel($layoutModel);
    }

    /**
     * Attach one or more listeners
     *
     * Implementors may add an optional $priority argument; the EventManager
     * implementation will pass this to the aggregate.
     *
     * @param EventManagerInterface $events
     *
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_HTML_BODY_PRE, array($this, 'injectLayoutViewModel'));
    }

    /**
     * @param  string $layoutTemplate
     * @return self
     */
    public function setLayoutTemplate($layoutTemplate)
    {
        $this->layoutTemplate = $layoutTemplate;

        return $this;
    }

    /**
     * @return string
     */
    public function getLayoutTemplate()
    {
        return $this->layoutTemplate;
    }
}
