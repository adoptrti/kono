<?php
/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_comm_member'); ?>
		<?php echo $form->textField($model,'id_comm_member'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_comm'); ?>
		<?php echo $form->textField($model,'id_comm'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_result'); ?>
		<?php echo $form->textField($model,'id_result'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'chairman'); ?>
		<?php echo $form->textField($model,'chairman'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->