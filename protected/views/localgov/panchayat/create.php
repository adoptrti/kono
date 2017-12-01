<?php
/* @var $this PanchayatController */
/* @var $model Panchayat */

$this->breadcrumbs=array(
	'Panchayats'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Panchayat', 'url'=>array('index')),
	array('label'=>'Manage Panchayat', 'url'=>array('admin')),
);
?>

<h1>Create Panchayat</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>