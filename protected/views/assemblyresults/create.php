<?php
/* @var $this AssemblyresultsController */
/* @var $model TamilNaduResults2016 */

$this->breadcrumbs=array(
	'Tamil Nadu Results2016s'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TamilNaduResults2016', 'url'=>array('index')),
	array('label'=>'Manage TamilNaduResults2016', 'url'=>array('admin')),
);
?>

<h1>Create TamilNaduResults2016</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>