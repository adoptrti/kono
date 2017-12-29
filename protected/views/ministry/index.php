<?php
/* @var $this MinistryController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ministries',
);

$this->menu=array(
	array('label'=>'Create Ministry', 'url'=>array('create')),
	array('label'=>'Manage Ministry', 'url'=>array('admin')),
);
?>

<h1>Ministries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
