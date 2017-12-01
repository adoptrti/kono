<?php
/* @var $this VillageController */
/* @var $model LBVillage */

$this->breadcrumbs=array(
	'Lbvillages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LBVillage', 'url'=>array('index')),
	array('label'=>'Manage LBVillage', 'url'=>array('admin')),
);
?>

<h1>Create LBVillage</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>