<?php
/* @var $this MinistryController */
/* @var $model Ministry */

$this->breadcrumbs=array(
	'Ministries'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Ministry', 'url'=>array('index')),
	array('label'=>'Manage Ministry', 'url'=>array('admin')),
);
?>

<h1>Create Ministry</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>