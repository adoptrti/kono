<?php
/* @var $this ElectionController */
/* @var $model Election */

$this->breadcrumbs=array(
	'Elections'=>array('index'),
	$model->id_election,
);

$this->menu=array(
	array('label'=>'List Election', 'url'=>array('index')),
	array('label'=>'Create Election', 'url'=>array('create')),
	array('label'=>'Update Election', 'url'=>array('update', 'id'=>$model->id_election)),
	array('label'=>'Delete Election', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_election),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Election', 'url'=>array('admin')),
);
?>

<h1>View Election #<?php echo $model->id_election; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_election',
		'id_state',
		'edate',
		'year',
		'type',
	),
)); ?>
