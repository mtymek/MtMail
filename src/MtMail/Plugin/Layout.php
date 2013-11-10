<?php

namespace MtMail\Plugin;


use MtMail\Event\ComposerEvent;
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
        $layoutModel = new ViewModel();
        $layoutModel->addChild($event->getViewModel());
        $layoutModel->setTemplate($this->layoutTemplate);
        $event->setViewModel($layoutModel);
    }

    /**
     * @param ComposerEvent $event
     */
    public function addEmailLayout(ComposerEvent $event)
    {

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
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_RENDER_PRE, array($this, 'injectLayoutViewModel'));
        $this->listeners[] = $events->attach(ComposerEvent::EVENT_RENDER_POST, array($this, 'addEmailLayout'));
    }
}