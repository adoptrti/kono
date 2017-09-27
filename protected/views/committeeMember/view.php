<?php
/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */

$this->breadcrumbs=array(
	'Committee Members'=>array('index'),
	$model->id_comm_member,
);

$this->menu=array(
	array('label'=>'List CommitteeMember', 'url'=>array('index')),
	array('label'=>'Create CommitteeMember', 'url'=>array('create')),
	array('label'=>'Update CommitteeMember', 'url'=>array('update', 'id'=>$model->id_comm_member)),
	array('label'=>'Delete CommitteeMember', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_comm_member),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CommitteeMember', 'url'=>array('admin')),
);
?>

<h1>View CommitteeMember #<?php echo $model->id_comm_member; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_comm_member',
		'id_comm',
		'id_result',
		'chairman',
	),
)); ?>
