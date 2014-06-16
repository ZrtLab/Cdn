<?php

namespace Cdn\View\Helper\Factory;

use Cdn\View\Helper\Css,
    Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class CssFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $config = $serviceLocator->get('Configuration');
        $helper = new Css();
        $helper->setConfig($config['cdn']);
        return $helper;
    }
}
