<?php
/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */

$this->breadcrumbs=array(
	'Committee Members'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CommitteeMember', 'url'=>array('index')),
	array('label'=>'Manage CommitteeMember', 'url'=>array('admin')),
);
?>

<h1>Create CommitteeMember</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>