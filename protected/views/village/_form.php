<?php
/* @var $this VillageController */
/* @var $model Village */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'village-form',
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
		<?php echo $form->labelEx($model,'id_district'); ?>
		<?php echo $form->textField($model,'id_district'); ?>
		<?php echo $form->error($model,'id_district'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'block'); ?>
		<?php echo $form->textField($model,'block',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'block'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'panchayat'); ?>
		<?php echo $form->textField($model,'panchayat',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'panchayat'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'village'); ?>
		<?php echo $form->textField($model,'village',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'village'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->