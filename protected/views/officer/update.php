<?php
/* @var $this OfficerController */
/* @var $model Officer */

$this->breadcrumbs=array(
	'Officers'=>array('index'),
	$model->name=>array('view','id'=>$model->id_officer),
	'Update',
);

$this->menu=array(
	array('label'=>'List Officer', 'url'=>array('index')),
	array('label'=>'Create Officer', 'url'=>array('create')),
	array('label'=>'View Officer', 'url'=>array('view', 'id'=>$model->id_officer)),
	array('label'=>'Manage Officer', 'url'=>array('admin')),
);
?>

<h1>Update Officer <?php echo $model->id_officer; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>