<?php
/* @var $this MinisterController */
/* @var $model Minister */

$this->breadcrumbs=array(
	'Ministers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Minister', 'url'=>array('index')),
	array('label'=>'Manage Minister', 'url'=>array('admin')),
);
?>

<h1>Create Minister</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>