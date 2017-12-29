<?php
/* @var $this MinisterController */
/* @var $model Minister */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'minister-form',
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
		<?php echo $form->labelEx($model,'id_member'); ?>
		<?php echo $form->textField($model,'id_member'); ?>
		<?php echo $form->error($model,'id_member'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'appointed_from'); ?>
		<?php echo $form->textField($model,'appointed_from'); ?>
		<?php echo $form->error($model,'appointed_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'appointed_to'); ?>
		<?php echo $form->textField($model,'appointed_to'); ?>
		<?php echo $form->error($model,'appointed_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
		<?php echo $form->error($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
		<?php echo $form->error($model,'updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_ministry'); ?>
		<?php echo $form->textField($model,'id_ministry'); ?>
		<?php echo $form->error($model,'id_ministry'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->