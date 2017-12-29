<?php
/* @var $this MinisterController */
/* @var $model Minister */

$this->breadcrumbs=array(
	'Ministers'=>array('index'),
	$model->id_minister=>array('view','id'=>$model->id_minister),
	'Update',
);

$this->menu=array(
	array('label'=>'List Minister', 'url'=>array('index')),
	array('label'=>'Create Minister', 'url'=>array('create')),
	array('label'=>'View Minister', 'url'=>array('view', 'id'=>$model->id_minister)),
	array('label'=>'Manage Minister', 'url'=>array('admin')),
);
?>

<h1>Update Minister <?php echo $model->id_minister; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>