<?php
/* @var $this OfficerController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Officers',
);

$this->menu=array(
	array('label'=>'Create Officer', 'url'=>array('create')),
	array('label'=>'Manage Officer', 'url'=>array('admin')),
);
?>

<h1>Officers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
