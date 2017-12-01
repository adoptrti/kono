<?php
/* @var $this AssemblyresultsController */
/* @var $model TamilNaduResults2016 */

$this->breadcrumbs=array(
	'Tamil Nadu Results2016s'=>array('index'),
	$model->name=>array('view','id'=>$model->id_result),
	'Update',
);

$this->menu=array(
	array('label'=>'List TamilNaduResults2016', 'url'=>array('index')),
	array('label'=>'Create TamilNaduResults2016', 'url'=>array('create')),
	array('label'=>'View TamilNaduResults2016', 'url'=>array('view', 'id'=>$model->id_result)),
	array('label'=>'Manage TamilNaduResults2016', 'url'=>array('admin')),
);
?>

<h1>Update TamilNaduResults2016 <?php echo $model->id_result; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>