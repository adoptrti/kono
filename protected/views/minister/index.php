<?php
/* @var $this MinisterController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ministers',
);

$this->menu=array(
	array('label'=>'Create Minister', 'url'=>array('create')),
	array('label'=>'Manage Minister', 'url'=>array('admin')),
);
?>

<h1>Ministers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
