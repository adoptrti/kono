<?php
/* @var $this VillageController */
/* @var $model LBVillage */

$this->breadcrumbs=array(
	'Lbvillages'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List LBVillage', 'url'=>array('index')),
	array('label'=>'Create LBVillage', 'url'=>array('create')),
	array('label'=>'Update LBVillage', 'url'=>array('update', 'id'=>$model->id_village)),
	array('label'=>'Delete LBVillage', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_village),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LBVillage', 'url'=>array('admin')),
);
?>

<h1>View LBVillage #<?php echo $model->id_village; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_village',
		'id_panchayat',
		'name',
		'updated',
	),
)); ?>
