<?php
Yii::import("application.urlrules.StateUrlRule");
foreach ( Yii::app ()->params ['translatedLanguages'] as $code => $lang ) {
	$su = new StateUrlRule ();
	$url = Yii::app ()->getRequest ()->getUrl ();
	if ('/' == $url [0])
		$url = substr ( $url, 1 );
	$backup = $_GET;
	$_GET = [ ];
	$rt = Yii::app()->controller->id . '/' . Yii::app()->controller->action->id;
	if ($rt === false) {
		$params2 = "?lang=$code";
	} else {
		$params = $_GET;
		$_GET = $backup;
		$params ['lang'] = $code;
		$params2 = array_merge ( [ 
				$rt 
		], $params );
		
		echo CHtml::tag ( 'link', [ 
				'rel' => 'alternate',
				'hreflang' => $code,
				'href' => Yii::app()->createAbsoluteUrl($rt,$params) 
		], false, false );
	}
}