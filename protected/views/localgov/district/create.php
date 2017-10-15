<?php
/* @var $this DistrictController */
/* @var $model District */

$this->breadcrumbs=array(
	'Districts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List District', 'url'=>array('index')),
	array('label'=>'Manage District', 'url'=>array('admin')),
);
?>

<h1>Create District</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>