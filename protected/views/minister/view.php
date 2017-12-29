<?php
/* @var $this MinisterController */
/* @var $model Minister */

$this->breadcrumbs=array(
	'Ministers'=>array('index'),
	$model->id_minister,
);

$this->menu=array(
	array('label'=>'List Minister', 'url'=>array('index')),
	array('label'=>'Create Minister', 'url'=>array('create')),
	array('label'=>'Update Minister', 'url'=>array('update', 'id'=>$model->id_minister)),
	array('label'=>'Delete Minister', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_minister),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Minister', 'url'=>array('admin')),
);
?>

<h1>View Minister #<?php echo $model->id_minister; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_state',
		'id_member',
		'appointed_from',
		'appointed_to',
		'created',
		'updated',
		'id_minister',
		'id_ministry',
	),
)); ?>
