<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - ' . __('Credits');
$this->breadcrumbs=array(
        __('Credits'),
);

$text = file_get_contents(Yii::app()->basePath . '/../Credits.md');
$md = new Parsedown;
echo $md->text($text);

?>
