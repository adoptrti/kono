<?php
/* @var $this DistrictController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Districts',
);

$this->menu=array(
	array('label'=>'Create District', 'url'=>array('create')),
    array('label'=>'Create Division', 'url'=>array('creatediv')),
	array('label'=>'Manage District', 'url'=>array('admin')),
);
?>

<h1>Districts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
