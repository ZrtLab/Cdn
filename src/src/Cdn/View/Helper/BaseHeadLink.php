<?php

namespace Cdn\View\Helper;

use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Uri\Http as HttpUri;
use Zend\View\Helper\HeadLink;

class BaseHeadLink extends HeadLink
{
    protected $allow = array('stylesheet');
    /**
     * Enable state
     * @var boolean
     */
    protected $enabled;

    /**
     * Cdn config, array of server config
     * @var array
     */
    protected $config;
    protected $servers = array();
    protected $nameServer = 'servers';
    protected $src;
    protected $assets;
    protected $prefix = 'css';
    protected $minimized = false;


    /**
     * Current server id used
     * @var integer
     */
    protected static $serverId;

    /**
     *
     * @var string
     */
    protected static $lastCommit;

    /**
     * Construct the cdn helper
     *
     * @param array $config
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * Get cdn link
     * @param string $src
     */
    public function cdn()
    {
        $this->assignPrefix();
        return $this->processUrl();
    }

    protected function assignPrefix()
    {
        if (!in_array($this->src->rel,$this->allow)) {
            return;
        }

        if(!isset($this->prefix)){
            throw new \Exception('No esta configurado un prefix', 500);
        }

        if(isset($this->assets['minimized'])){
            $this->minimized = $this->assets['minimized'];
        }

        if(!$this->minimized){
            $this->src->href = '/' . $this->prefix . $this->src->href;
            return;
        }

        $this->src->href = '/' . $this->assets['prefix'][$this->prefix] . $this->src->href;

    }

    protected function processUrl()
    {
        $config = $this->servers[static::$serverId];
        $uri = new HttpUri($this->src->href);
        if ($uri->getHost()) {
            return $uri->toString();
        }
        $uri->setScheme($config->scheme);
        $uri->setPort($config->port);
        $uri->setHost($config->host);
        $uri->setQuery(static::$lastCommit);

        $this->src->href = $uri->toString();
        return $this->src;
    }

    public function setConfig(array $config)
    {
        if (empty($config)) {
            throw new InvalidArgumentException('Cdn config must be not empty');
        }
        $this->config = $config;
        $this->setupParams();
        return $this;
    }

    /**
     * Set enable state
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
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
     * append()
     *
     * @param  array                              $value
     * @return void
     * @throws Exception\InvalidArgumentException
     */
    public function append($value)
    {
        $this->src = $value;
        parent::append($this->cdn());
    }

    /**
     * prepend()
     *
     * @param  array                              $value
     * @return HeadLink
     * @throws Exception\InvalidArgumentException
     */
    public function prepend($value)
    {
        $this->src = $value;
        parent::prepend($this->cdn());
    }

    /**
     * set()
     *
     * @param  array                              $value
     * @return HeadLink
     * @throws Exception\InvalidArgumentException
     */
    public function set($value)
    {
        $this->src = $value;
        parent::set($this->cdn());
    }

    /**
     * offsetSet()
     *
     * @param  string|int                         $index
     * @param  array                              $value
     * @return void
     * @throws Exception\InvalidArgumentException
     */
    public function offsetSet($index, $value)
    {
        $this->src = $value;
        parent::offsetSet($index, $this->cdn());
    }

    protected function checkCdn()
    {
        if (!$this->enabled) {
            return $this->src;
        }

        if (!is_object($this->src)) {
            throw new InvalidArgumentException('Source image must be a string');
        }

        if (!isset($this->servers[static::$serverId])) {
            throw new \Exception("No se encuentra el Haystack Mencionado",500);
        }

    }

    protected function setupParams()
    {
        if(!$this->config){
            throw new \Exception("No Existe Configuración", 500);
        }

        $this->enabled = true;
        if(isset($this->config['link_helper']['enabled'])){
            $this->enabled = $this->config['link_helper']['enabled'];
        }
        $this->servers = array();

        foreach ($this->config[$this->nameServer] as $server) {
            if (!is_array($server)) {
                throw new InvalidArgumentException('server config must be an array of server arrays');
            }

            $this->servers[] = (object) $server;
        }
        static::$serverId = 0;
        static::$lastCommit = $this->getLastCommit();
        if(!isset($this->config['assets'])){
            throw new \Exception('No se a especificado el config Assets', 500);
        }

        $this->assets = $this->config['assets'];
    }

    /**
     *
     * @todo upgrade codigo
     * @return string
     */
    public function getUrl()
    {
        $uri = new HttpUri();
        $config = $this->servers[static::$serverId];
        $uri->setScheme($config->scheme);
        $uri->setPort($config->port);
        $uri->setHost($config->host);

        return $uri->toString();
    }

    public function getLastCommit()
    {
        $lc_file = ROOT_PATH . '/last_commit';

        if (is_readable($lc_file)) {
            if (!isset(static::$lastCommit)) {
                static::$lastCommit = trim(file_get_contents($lc_file));
            }
        }

        return static::$lastCommit;
    }

}
