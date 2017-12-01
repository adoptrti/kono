<?php
/* @var $this AssemblyresultsController */
/* @var $model TamilNaduResults2016 */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tamil-nadu-results2016-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'id_election'); ?>
		<?php echo $form->textField($model,'id_election'); ?>
		<?php echo $form->error($model,'id_election'); ?>
	</div>

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
		<?php echo $form->labelEx($model,'acname'); ?>
		<?php echo $form->textField($model,'acname',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'acname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acno'); ?>
		<?php echo $form->textField($model,'acno'); ?>
		<?php echo $form->error($model,'acno'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gender'); ?>
		<?php echo $form->textField($model,'gender',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'gender'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'party'); ?>
		<?php echo $form->textField($model,'party',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'party'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phones'); ?>
		<?php echo $form->textField($model,'phones',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'phones'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'emails'); ?>
		<?php echo $form->textField($model,'emails',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'emails'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'ST_CODE'); ?>
		<?php echo $form->textField($model,'ST_CODE'); ?>
		<?php echo $form->error($model,'ST_CODE'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'picture'); ?>
		<?php echo $form->textField($model,'picture',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'picture'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->