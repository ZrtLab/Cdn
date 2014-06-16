<?php

namespace Cdn\View\Helper\Factory;

use Cdn\View\Helper\Link;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LinkFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $config = $serviceLocator->get('Configuration');
        $helper = new Link();
        $helper->setConfig($config['cdn']);
        return $helper;
    }

}
