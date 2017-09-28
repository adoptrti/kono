<?php
/* @var $this StateController */
/* @var $model State */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'state-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'ST_CODE'); ?>
		<?php echo $form->textField($model,'ST_CODE'); ?>
		<?php echo $form->error($model,'ST_CODE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ias_short_code'); ?>
		<?php echo $form->textField($model,'ias_short_code',array('size'=>2,'maxlength'=>2)); ?>
		<?php echo $form->error($model,'ias_short_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'id_census'); ?>
		<?php echo $form->textField($model,'id_census'); ?>
		<?php echo $form->error($model,'id_census'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'eci_ref'); ?>
		<?php echo $form->textField($model,'eci_ref',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'eci_ref'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'session_from'); ?>
		<?php echo $form->textField($model,'session_from'); ?>
		<?php echo $form->error($model,'session_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'session_to'); ?>
		<?php echo $form->textField($model,'session_to'); ?>
		<?php echo $form->error($model,'session_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lok_parl_seats'); ?>
		<?php echo $form->textField($model,'lok_parl_seats'); ?>
		<?php echo $form->error($model,'lok_parl_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amly_seats'); ?>
		<?php echo $form->textField($model,'amly_seats'); ?>
		<?php echo $form->error($model,'amly_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'raj_parl_seats'); ?>
		<?php echo $form->textField($model,'raj_parl_seats'); ?>
		<?php echo $form->error($model,'raj_parl_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
		<?php echo $form->error($model,'updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'iso3166'); ?>
		<?php echo $form->textField($model,'iso3166',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'iso3166'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'psloc'); ?>
		<?php echo $form->textField($model,'psloc'); ?>
		<?php echo $form->error($model,'psloc'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'eci_dist_count'); ?>
		<?php echo $form->textField($model,'eci_dist_count'); ?>
		<?php echo $form->error($model,'eci_dist_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'eci_amly_count'); ?>
		<?php echo $form->textField($model,'eci_amly_count'); ?>
		<?php echo $form->error($model,'eci_amly_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slug'); ?>
		<?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'slug'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'mcorp_count'); ?>
		<?php echo $form->textField($model,'mcorp_count'); ?>
		<?php echo $form->error($model,'mcorp_count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->