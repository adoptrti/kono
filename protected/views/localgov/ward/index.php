<?php
/* @var $this WardController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lbwards',
);

$this->menu=array(
	array('label'=>'Create LBWard', 'url'=>array('create')),
	array('label'=>'Manage LBWard', 'url'=>array('admin')),
);
?>

<h1>Lbwards</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
