<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../../yii/framework/yiic.php';
$config=dirname(__FILE__).'/config/console.php';

function __($string, $params = array(), $category = "")
{
    if(class_exists('Yii'))
        return Yii::t($category, $string, $params);
        return $string;
}

require_once($yiic);
