<?php

namespace Cdn\View\Helper;
use Zend\Uri\Http as HttpUri;

class Assets extends Link
{

    protected $assets;
    protected $prefix;
    protected $minimized = false;

    protected function setupParams()
    {
        parent::setupParams();

        if(!isset($this->config['assets'])){
            throw new \Exception('No se a especificado el config Assets', 500);
        }

        $this->assets = $this->config['assets'];
    }

    /**
     * Get cdn link
     * @param string $src
     */
    public function cdn()
    {
        $this->assignPrefix();
        return parent::cdn();
    }

    protected function assignPrefix()
    {
        if(!isset($this->prefix)){
            throw new \Exception('No esta configurado un prefix', 500);
        }

        if(isset($this->assets['minimized'])){
            $this->minimized = $this->assets['minimized'];
        }

        if(!$this->minimized){
            $this->src = '/' . $this->prefix . $this->src;
            return;
        }

        $this->src = '/' . $this->assets['prefix'][$this->prefix] . $this->src;

    }

    public function getUrl()
    {
        $this->assignPrefix();
        $uri = new HttpUri($this->src);
        $config = $this->servers[static::$serverId];
        $uri->setScheme($config->scheme);
        $uri->setPort($config->port);
        $uri->setHost($config->host);

        return $uri->toString();
    }

}
