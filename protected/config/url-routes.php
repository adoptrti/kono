<?php
return array (
        'class' => 'application.urlrules.BetterUrlManager',
        'urlFormat' => 'path',
        'showScriptName' => false,
        'caseSensitive' => false,
        'rules' => array (
                
                array (
                        'class' => 'application.urlrules.StateUrlRule',
                        'connectionID' => 'db'
                ),
                
                '<lang:\w\w>/states' => 'state/index',
                'site/page/<view:\w+>' => 'site/page',
                '/' => 'site/index',
                
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                
                '<controller:api>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                
                '<lang:\w\w>/' => 'site/index',
        ) 
);
                