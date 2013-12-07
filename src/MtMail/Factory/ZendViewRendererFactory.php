<?php

namespace MtMail\Factory;

use MtMail\Renderer\ZendView;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZendViewRendererFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $view = $serviceLocator->get('ViewManager')->getView();
        $service = new ZendView($view);
        return $service;
    }
}
