<?php
/* @var $this AssemblyresultsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tamil Nadu Results2016s',
);

$this->menu=array(
	array('label'=>'Create TamilNaduResults2016', 'url'=>array('create')),
	array('label'=>'Manage TamilNaduResults2016', 'url'=>array('admin')),
);
?>

<h1>Tamil Nadu Results2016s</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
