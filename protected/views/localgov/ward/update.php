<?php
/* @var $this WardController */
/* @var $model LBWard */

$this->breadcrumbs=array(
	'Lbwards'=>array('index'),
	$model->name=>array('view','id'=>$model->id_vward),
	'Update',
);

$this->menu=array(
	array('label'=>'List LBWard', 'url'=>array('index')),
	array('label'=>'Create LBWard', 'url'=>array('create')),
	array('label'=>'View LBWard', 'url'=>array('view', 'id'=>$model->id_vward)),
	array('label'=>'Manage LBWard', 'url'=>array('admin')),
);
?>

<h1>Update LBWard <?php echo $model->id_vward; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>