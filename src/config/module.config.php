<?php

return array(
    'view_helpers' => array(
        'invokables' => array(
            'routerHelper' => 'Cdn\View\Helper\Router'
        ),
        'factories' => array(
            'headLinkCdn' => 'Cdn\View\Helper\Factory\HeadLinkFactory',
            'headScriptCdn' => 'Cdn\View\Helper\Factory\HeadScriptFactory',
            'linkCdn' => 'Cdn\View\Helper\Factory\LinkFactory',
            'jsCdn' => 'Cdn\View\Helper\Factory\JsFactory',
            'cssCdn' => 'Cdn\View\Helper\Factory\CssFactory',
            'linkElements' => 'Cdn\View\Helper\Factory\LinkElementsFactory',
        ),
        'aliases' => array(
            'headLink' => 'headLinkCdn',
            'headScript' => 'headScriptCdn',
            'router' => 'routerHelper',
        ),
    ),
);
