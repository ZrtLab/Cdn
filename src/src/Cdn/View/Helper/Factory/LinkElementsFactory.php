<?php

namespace Cdn\View\Helper\Factory;

use Cdn\View\Helper\Elements;
use Zend\ServiceManager\FactoryInterface,
    Zend\ServiceManager\ServiceLocatorInterface;

class LinkElementsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $config = $serviceLocator->get('Config');
        $helper = new Elements();
        $helper->setConfig($config['cdn']);

        return $helper;
    }

}
