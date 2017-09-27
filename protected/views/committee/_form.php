<?php
/* @var $this CommitteeController */
/* @var $model Committee */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'committee-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_state'); ?>
		<?php echo $form->textField($model,'id_state'); ?>
		<?php echo $form->error($model,'id_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_consti'); ?>
		<?php echo $form->textField($model,'id_consti'); ?>
		<?php echo $form->error($model,'id_consti'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ctype'); ?>
		<?php echo $form->textField($model,'ctype',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'ctype'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_election'); ?>
		<?php echo $form->textField($model,'id_election'); ?>
		<?php echo $form->error($model,'id_election'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->