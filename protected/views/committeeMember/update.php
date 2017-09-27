<?php
/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */

$this->breadcrumbs=array(
	'Committee Members'=>array('index'),
	$model->id_comm_member=>array('view','id'=>$model->id_comm_member),
	'Update',
);

$this->menu=array(
	array('label'=>'List CommitteeMember', 'url'=>array('index')),
	array('label'=>'Create CommitteeMember', 'url'=>array('create')),
	array('label'=>'View CommitteeMember', 'url'=>array('view', 'id'=>$model->id_comm_member)),
	array('label'=>'Manage CommitteeMember', 'url'=>array('admin')),
);
?>

<h1>Update CommitteeMember <?php echo $model->id_comm_member; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>