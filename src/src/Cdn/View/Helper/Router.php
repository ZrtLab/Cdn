<?php

namespace Cdn\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Router extends AbstractHelper implements matchAwareInterface
{

    protected $match;
    protected $attributes = array(
        '__NAMESPACE__', '__CONTROLLER__', 'controller'
    );

    public function setMatch($match)
    {
        $this->match = $match;
    }

    /**
     * @todo upgrade code
     */
    protected function getParams()
    {
        $tmp = $this->match->getParams();
        foreach ($tmp as $key => $value) {
            if (in_array($key, $this->attributes)) {
                $tmp[$key] = addslashes($value);
            }
        }

        $modulo = explode('\\',
            isset($tmp['__NAMESPACE__']) ?
                $tmp['__NAMESPACE__'] : null);
        $module = strtolower(isset($modulo['0']) ? $modulo['0'] : null);
        $controller = strtolower(isset($tmp['__CONTROLLER__']) ? $tmp['__CONTROLLER__']
                    : null);
        $action = strtolower(isset($tmp['action']) ? $tmp['action'] : null);

        $params['module'] = $module;
        $params['controller'] = $controller;
        $params['action'] = $action;

        return $params;
    }

    public function __invoke()
    {
        return $this->getParams();
    }

}
