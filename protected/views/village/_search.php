<?php
/* @var $this VillageController */
/* @var $model Village */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_village'); ?>
		<?php echo $form->textField($model,'id_village'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_state'); ?>
		<?php echo $form->textField($model,'id_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_district'); ?>
		<?php echo $form->textField($model,'id_district'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'block'); ?>
		<?php echo $form->textField($model,'block',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'panchayat'); ?>
		<?php echo $form->textField($model,'panchayat',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'village'); ?>
		<?php echo $form->textField($model,'village',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->