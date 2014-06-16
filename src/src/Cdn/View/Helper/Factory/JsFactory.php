<?php

namespace Cdn\View\Helper\Factory;

use Cdn\View\Helper\Js,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class JsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $config = $serviceLocator->get('Configuration');
        $helper = new Js();
        $helper->setConfig($config['cdn']);
        return $helper;
    }
}
