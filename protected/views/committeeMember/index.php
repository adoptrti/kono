<?php
/* @var $this CommitteeMemberController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Committee Members',
);

$this->menu=array(
	array('label'=>'Create CommitteeMember', 'url'=>array('create')),
	array('label'=>'Manage CommitteeMember', 'url'=>array('admin')),
);
?>

<h1>Committee Members</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
