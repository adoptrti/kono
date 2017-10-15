<?php
/* @var $this WardController */
/* @var $model LBWard */

$this->breadcrumbs=array(
	'Lbwards'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List LBWard', 'url'=>array('index')),
	array('label'=>'Create LBWard', 'url'=>array('create')),
	array('label'=>'Update LBWard', 'url'=>array('update', 'id'=>$model->id_vward)),
	array('label'=>'Delete LBWard', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_vward),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LBWard', 'url'=>array('admin')),
);
?>

<h1>View LBWard #<?php echo $model->id_vward; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_vward',
		'id_village',
		'name',
		'updated',
	),
)); ?>
