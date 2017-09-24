<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - About';
$this->breadcrumbs=array(
	'About',
);

$text = file_get_contents(Yii::app()->basePath . '/../README.md');
$md = new CMarkdown;
echo $md->transform($text);

?>
