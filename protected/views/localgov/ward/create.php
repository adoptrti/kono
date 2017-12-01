<?php
/* @var $this WardController */
/* @var $model LBWard */

$this->breadcrumbs=array(
	'Lbwards'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LBWard', 'url'=>array('index')),
	array('label'=>'Manage LBWard', 'url'=>array('admin')),
);
?>

<h1>Create LBWard</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>