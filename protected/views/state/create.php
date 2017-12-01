<?php
/* @var $this StateController */
/* @var $model State */
/* @var $id_state integer */

$this->breadcrumbs=array(
	'States'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List State', 'url'=>array('index')),
	array('label'=>'Manage State', 'url'=>array('admin')),
);
?>

<h1>Create State</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'id_state' => $id_state)); ?>