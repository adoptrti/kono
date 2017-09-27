<?php
/* @var $this CommitteeMemberController */
/* @var $model CommitteeMember */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'committee-member-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_comm'); ?>
		<?php echo $form->textField($model,'id_comm'); ?>
		<?php echo $form->error($model,'id_comm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_result'); ?>
		<?php echo $form->textField($model,'id_result'); ?>
		<?php echo $form->error($model,'id_result'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'chairman'); ?>
		<?php echo $form->textField($model,'chairman'); ?>
		<?php echo $form->error($model,'chairman'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->