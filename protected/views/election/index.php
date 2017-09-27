<?php
/* @var $this ElectionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Elections',
);

$this->menu=array(
	array('label'=>'Create Election', 'url'=>array('create')),
	array('label'=>'Manage Election', 'url'=>array('admin')),
);
?>

<h1>Elections</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
