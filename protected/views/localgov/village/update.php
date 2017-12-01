<?php
/* @var $this VillageController */
/* @var $model LBVillage */

$this->breadcrumbs=array(
	'Lbvillages'=>array('index'),
	$model->name=>array('view','id'=>$model->id_village),
	'Update',
);

$this->menu=array(
	array('label'=>'List LBVillage', 'url'=>array('index')),
	array('label'=>'Create LBVillage', 'url'=>array('create')),
	array('label'=>'View LBVillage', 'url'=>array('view', 'id'=>$model->id_village)),
	array('label'=>'Manage LBVillage', 'url'=>array('admin')),
);
?>

<h1>Update LBVillage <?php echo $model->id_village; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>