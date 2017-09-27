<?php
/* @var $this ElectionController */
/* @var $model Election */

$this->breadcrumbs=array(
	'Elections'=>array('index'),
	$model->id_election=>array('view','id'=>$model->id_election),
	'Update',
);

$this->menu=array(
	array('label'=>'List Election', 'url'=>array('index')),
	array('label'=>'Create Election', 'url'=>array('create')),
	array('label'=>'View Election', 'url'=>array('view', 'id'=>$model->id_election)),
	array('label'=>'Manage Election', 'url'=>array('admin')),
);
?>

<h1>Update Election <?php echo $model->id_election; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>