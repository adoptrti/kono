<?php
/* @var $this ElectionController */
/* @var $model Election */

$this->breadcrumbs=array(
	'Elections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Election', 'url'=>array('index')),
	array('label'=>'Manage Election', 'url'=>array('admin')),
);
?>

<h1>Create Election</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>