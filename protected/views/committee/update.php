<?php
/* @var $this CommitteeController */
/* @var $model Committee */

$this->breadcrumbs=array(
	'Committees'=>array('index'),
	$model->name=>array('view','id'=>$model->id_comm),
	'Update',
);

$this->menu=array(
	array('label'=>'List Committee', 'url'=>array('index')),
	array('label'=>'Create Committee', 'url'=>array('create')),
	array('label'=>'View Committee', 'url'=>array('view', 'id'=>$model->id_comm)),
	array('label'=>'Manage Committee', 'url'=>array('admin')),
);
?>

<h1>Update Committee <?php echo $model->id_comm; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>