<?php

namespace Cdn\Tests;

use Cdn\Module as CdnModule,
    Zend\ServiceManager\Config as ServiceConfig,
    Zend\ServiceManager\ServiceManager;

abstract class BaseModuleTest extends \PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $module = new CdnModule();
        $config = $module->getConfig();
        $this->assertNotEmpty($config);
        $this->assertInternalType('array',$config);

        $serviceConfig = new ServiceConfig($config['service_manager']);
        $this->serviceManager = new ServiceManager($serviceConfig);

        $this->assertInstanceOf('Zend\ServiceManager\ServiceManager', $this->serviceManager);

    }

    /**
     * Instantiate the Service Manager with the configuration from the RabbitClient
     *  module
     *
     * @return ServiceManager
     */
    protected function createServiceManagerForTest()
    {
        $module = new CdnModule();
        $config = $module->getConfig();
        $this->assertNotEmpty($config);
        $this->assertInternalType('array', $config);

        $serviceConfig  = new ServiceConfig($config['service_manager']);
        $this->assertNotEmpty($serviceConfig);

        $serviceManager = new ServiceManager($serviceConfig);

        $this->assertInstanceOf('Zend\ServiceManager\ServiceManager', $serviceManager);

        return $serviceManager;
    }
}
