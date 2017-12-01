<?php
/* @var $this VillageController */
/* @var $model Village */

$this->breadcrumbs=array(
	'Villages'=>array('index'),
	$model->id_village=>array('view','id'=>$model->id_village),
	'Update',
);

$this->menu=array(
	array('label'=>'List Village', 'url'=>array('index')),
	array('label'=>'Create Village', 'url'=>array('create')),
	array('label'=>'View Village', 'url'=>array('view', 'id'=>$model->id_village)),
	array('label'=>'Manage Village', 'url'=>array('admin')),
);
?>

<h1>Update Village <?php echo $model->id_village; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>