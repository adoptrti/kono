<?php
/* @var $this CommitteeController */
/* @var $model Committee */

$this->breadcrumbs=array(
	'Committees'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Committee', 'url'=>array('index')),
	array('label'=>'Manage Committee', 'url'=>array('admin')),
);
?>

<h1>Create Committee</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>