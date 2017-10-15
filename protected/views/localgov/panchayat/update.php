<?php
/* @var $this PanchayatController */
/* @var $model Panchayat */

$this->breadcrumbs=array(
	'Panchayats'=>array('index'),
	$model->name=>array('view','id'=>$model->id_panchayat),
	'Update',
);

$this->menu=array(
	array('label'=>'List Panchayat', 'url'=>array('index')),
	array('label'=>'Create Panchayat', 'url'=>array('create')),
	array('label'=>'View Panchayat', 'url'=>array('view', 'id'=>$model->id_panchayat)),
	array('label'=>'Manage Panchayat', 'url'=>array('admin')),
);
?>

<h1>Update Panchayat <?php echo $model->id_panchayat; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>