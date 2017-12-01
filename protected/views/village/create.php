<?php
/* @var $this VillageController */
/* @var $model Village */

$this->breadcrumbs=array(
	'Villages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Village', 'url'=>array('index')),
	array('label'=>'Manage Village', 'url'=>array('admin')),
);
?>

<h1>Create Village</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>