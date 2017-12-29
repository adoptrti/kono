<?php
/* @var $this MinistryController */
/* @var $model Ministry */

$this->breadcrumbs=array(
	'Ministries'=>array('index'),
	$model->name=>array('view','id'=>$model->id_ministry),
	'Update',
);

$this->menu=array(
	array('label'=>'List Ministry', 'url'=>array('index')),
	array('label'=>'Create Ministry', 'url'=>array('create')),
	array('label'=>'View Ministry', 'url'=>array('view', 'id'=>$model->id_ministry)),
	array('label'=>'Manage Ministry', 'url'=>array('admin')),
);
?>

<h1>Update Ministry <?php echo $model->id_ministry; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>