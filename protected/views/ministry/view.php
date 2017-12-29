<?php
/* @var $this MinistryController */
/* @var $model Ministry */

$this->breadcrumbs=array(
	'Ministries'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Ministry', 'url'=>array('index')),
	array('label'=>'Create Ministry', 'url'=>array('create')),
	array('label'=>'Update Ministry', 'url'=>array('update', 'id'=>$model->id_ministry)),
	array('label'=>'Delete Ministry', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_ministry),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ministry', 'url'=>array('admin')),
);
?>

<h1>View Ministry #<?php echo $model->id_ministry; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_ministry',
		'name',
		'created',
	),
)); ?>
