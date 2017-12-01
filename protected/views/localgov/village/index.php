<?php
/* @var $this VillageController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lbvillages',
);

$this->menu=array(
	array('label'=>'Create LBVillage', 'url'=>array('create')),
	array('label'=>'Manage LBVillage', 'url'=>array('admin')),
);
?>

<h1>Lbvillages</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
