<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array (
        'basePath' => dirname ( __FILE__ ) . DIRECTORY_SEPARATOR . '..',
        'name' => 'Kono',
        
        // preloading 'log' component
        'preload' => array (
                'log' 
        ),
        
        // autoloading model and component classes
        'import' => include __DIR__ . '/main-imports.php',
        
        // application components
        'components' => array (
                
                'user' => array (
                        // enable cookie-based authentication
                        'allowAutoLogin' => true 
                ),
                
                // uncomment the following to enable URLs in path-format
                
                'urlManager' => array (
                        'urlFormat' => 'path',
                        'showScriptName' => false,
                        'rules' => [ 
                                'site/page/<view:\w+>' => 'site/page',
                                '/' => 'site/index',
                                
                                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                                '<controller:\w+>/<action:\w+>' => '<controller>/<action>' 
                        
                        ] 
                ),
                
                // database settings are configured in database.php
                'db' => require (dirname ( __FILE__ ) . '/database.php'),
                
                'errorHandler' => array (
                        // use 'site/error' action to display errors
                        'errorAction' => YII_DEBUG ? null : 'site/error' 
                ),
                
                'clientScript' => array (
                        'class' => 'MyClientScript',
                        'CoreScriptUrl' => '/js',
                        'coreScriptPosition' => CClientScript::POS_END,
                        'packages' => include __DIR__ . '/script-packages.php',
                        'scriptMap' => array (
                            // 'jquery.js' => false,
                            // 'jquery.min.js' => false
                        ) 
                ), // clientScript
                
                'log' => array (
                        'class' => 'CLogRouter',
                        'routes' => array (
                                array (
                                        'class' => 'CFileLogRoute',
                                        'levels' => 'error, warning, info' 
                                ),
                                // uncomment the following to show log messages
                                // on web pages
                                
                                array (
                                        'class' => 'CWebLogRoute' 
                                ) 
                        
                        ) 
                ) 
        
        ),
        
        // application-level parameters that can be accessed
        // using Yii::app()->params['paramName']
        'params' => array (
                // this is used in contact page
                'defaultLanguage' => 'en',
                'defaultDBLanguage' => 'en',
                'translatedLanguages' => array (
                        'en' => 'English',
                        'hi' => 'Hindi',
                        'te' => __('Telegu'),// తెలుగు
                        'kn' => __('Kannada'),
                        'ta' => __('Tamil'),
                        'ml' => __('Malayalam'),
                ),
                'adminEmail' => 'webmaster@example.com',
                'google-api-key' => '-add-key-here-',
                'google-tracking-id' => '-add-key-here-' 
        ) 
);
