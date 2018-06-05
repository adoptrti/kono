<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../vendor/yiisoft/yii/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
#require_once(dirname(__FILE__).'/WebTestCase.php');

function __($string, $params = array(), $category = "")
{
	if(class_exists('Yii'))
		return Yii::t($category, $string, $params);
		return $string;
}

Yii::createWebApplication($config);
