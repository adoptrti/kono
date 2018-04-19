<?php

function __($string, $params = array(), $category = "")
{
    if(class_exists('Yii'))
        return Yii::t($category, $string, $params);
        return $string;
}


// change the following paths if necessary
$yii=dirname(__FILE__).'/../../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

define('DS',            DIRECTORY_SEPARATOR);
define('DIR_ROOT',      getcwd() . DS);
$autoload = DIR_ROOT . 'protected' . DS . 'vendor' . DS . 'autoload.php';
require_once($autoload);

require_once($yii);
Yii::createWebApplication($config)->run();