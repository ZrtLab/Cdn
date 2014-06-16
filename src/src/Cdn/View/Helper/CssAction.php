<?php

namespace Cdn\View\Helper;

use Zend\View\Helper\AbstractHelper,
    Zend\Stdlib\Exception\InvalidArgumentException;

class CssAction extends AbstractHelper
{

    /**
     * Enable state
     * @var boolean
     */
    protected $enabled;

    /**
     *
     * @var type
     */
    protected $cssConfig;

    public function __construct(array $cdnConfig, $enabled)
    {
        $this->setCssConfig($cdnConfig);
        $this->setEnabled($enabled);
    }

    /**
     * Set the Cdn servers config
     *
     * @param  array    $cdnConfig
     * @return HeadLink
     */
    public function setCssConfig(array $cdnConfig)
    {
        if (empty($cdnConfig)) {
            throw new InvalidArgumentException('Cdn config must be not empty');
        }
        $this->cssConfig = $cdnConfig;
    }

    /**
     * @todo upgrade css
     */
    public function createCss()
    {
        $this->generateAll();
        $this->generateCssModule();
        //$this->generateCssController();
        //$this->generateCssAction();
    }

    /**
     *
     */
    protected function generateAll()
    {
        $this->view->router();
        $config = $this->cssConfig['all'];

        if (empty($config)) {
            return null;
        }
        foreach ($config as $key => $value) {

            $this->view->HeadLink()->prependStylesheet($key, $value);
        }
    }

    protected function generateCssModule()
    {
        $listConfig = $this->listConfig('module');
        $config = $this->cssConfig['module'];
        $params = $this->view->router();
        $module = $params['module'];

        if (empty($config[$module]) || !in_array($module, $listConfig)) {
            return null;
        }

        foreach ($config[$module] as $key => $value) {
            $this->view->HeadLink()->prependStylesheet($key, $value);
        }
    }

    protected function generateCssController()
    {
        $listConfig = $this->listConfig('module/controller');
        $config = $this->cssConfig['module/controller'];
        $params = $this->view->router();
        $check = sprintf("%s/%s", $params['module'], $params['controller']);

        if (empty($config[$check]) || !in_array($check, $listConfig)) {
            return null;
        }

        foreach ($config[$check] as $key => $value) {
            $this->view->HeadLink()->prependStylesheet($key, $value);
        }
    }

    protected function generateCssAction()
    {
        $listConfig = $this->listConfig('module/controller/action');
        $config = $this->cssConfig['module/controller/action'];
        $params = $this->view->router();
        $check = sprintf("%s/%s/%s", $params['module'], $params['controller'],
            $params['action']);

        if (empty($config[$check]) || !in_array($check, $listConfig)) {
            return null;
        }

        foreach ($config[$check] as $key => $value) {
            $this->view->HeadLink()->prependStylesheet($key, $value);
        }
    }

    private function listConfig($key)
    {
        return array_keys($this->cssConfig[$key]);
    }

    /**
     * Get enable state
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enable state
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Usage of image view helper
     * @param  string $src
     * @return Image
     */
    public function __invoke()
    {
        return $this->createCss();
    }

}
