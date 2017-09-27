<?php
/* @var $this CommitteeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Committees',
);

$this->menu=array(
	array('label'=>'Create Committee', 'url'=>array('create')),
	array('label'=>'Manage Committee', 'url'=>array('admin')),
);
?>

<h1>Committees</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
