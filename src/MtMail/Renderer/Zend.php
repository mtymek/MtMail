<?php

namespace MtMail\Renderer;


use Zend\View\Model\ModelInterface;
use Zend\View\View;
use Zend\View\ViewEvent;

/**
 * Re-use Zend\View in order to render template with children
 */
class Zend extends View implements RendererInterface
{
    /**
     * @var string
     */
    protected $output;

    /**
     * @param string $output
     * @return self
     */
    public function setOutput($output)
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Class constructor
     *
     * Ensures output from Zend\View is captured
     */
    public function __construct()
    {
        $self = $this;
        $this->getEventManager()->attach(ViewEvent::EVENT_RESPONSE, function (ViewEvent $event) use ($self) {
                $self->setOutput($event->getResult());
            });
    }

    /**
     *
     * @param ModelInterface $model
     * @return mixed|string|void
     */
    public function render(ModelInterface $model)
    {
        parent::render($model);
        return $this->output;
    }
}