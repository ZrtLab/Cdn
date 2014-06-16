<?php

namespace Cdn;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\Mvc\ModuleRouteListener;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{

    public function onBootstrap($event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ .
            '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        //ServiceLocatorAwareInterface
    }

    /**
     *
     * @return type
     */
    public function getViewHelperConfig()
    {
        return array(
            'initializers' => array(
                'serviceLocator' => function ($instance, $sm) {
                    if ($instance instanceof View\Helper\matchAwareInterface) {
                        $match = $sm->getServiceLocator()
                            ->get('application')
                            ->getMvcEvent()
                            ->getRouteMatch();
                        $instance->setMatch($match);
                    }
                }
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            )
        );
    }

}
