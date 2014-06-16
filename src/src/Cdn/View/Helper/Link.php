<?php

namespace Cdn\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Uri\Http as HttpUri;

class Link extends AbstractHelper
{

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
        return $this;
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
    }

    /**
     * Usage of image view helper
     * @param  string $src
     * @return Image
     */
    public function __invoke($src = null)
    {
        if (null === $src) {
            return $this;
        }
        $this->src = $src;

        return $this->cdn();
    }

    protected function checkCdn()
    {
        if (!$this->enabled) {
            return $this->src;
        }

        if (!is_string($this->src)) {
            throw new InvalidArgumentException('Source image must be a string');
        }

        if (!isset($this->servers[static::$serverId])) {
            throw new \Exception("No se encuentra el Haystack Mencionado",500);
        }

    }

    /**
     * Get cdn link
     * @param string $src
     */
    public function cdn()
    {
        $this->checkCdn();
        return $this->processUrl();
    }


    protected function processUrl()
    {
        $config = $this->servers[static::$serverId];
        $uri = new HttpUri($this->src);
        if ($uri->getHost()) {
            return $uri->toString();
        }
        $uri->setScheme($config->scheme);
        $uri->setPort($config->port);
        $uri->setHost($config->host);
        $uri->setQuery(static::$lastCommit);

        return $uri->toString();
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
