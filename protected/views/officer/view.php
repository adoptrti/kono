<?php
/* @var $this OfficerController */
/* @var $model Officer */

$this->breadcrumbs=array(
	'Officers'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Officer', 'url'=>array('index')),
	array('label'=>'Create Officer', 'url'=>array('create')),
	array('label'=>'Update Officer', 'url'=>array('update', 'id'=>$model->id_officer)),
	array('label'=>'Delete Officer', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_officer),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Officer', 'url'=>array('admin')),
);
?>

<h1>View Officer #<?php echo $model->id_officer; ?></h1>

<?php $this->renderPartial('_view',['data' => $model,'full' => true]) ?>
