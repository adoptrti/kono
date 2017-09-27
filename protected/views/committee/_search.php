<?php
/* @var $this CommitteeController */
/* @var $model Committee */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id_state'); ?>
		<?php echo $form->textField($model,'id_state'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_consti'); ?>
		<?php echo $form->textField($model,'id_consti'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ctype'); ?>
		<?php echo $form->textField($model,'ctype',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_comm'); ?>
		<?php echo $form->textField($model,'id_comm'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_election'); ?>
		<?php echo $form->textField($model,'id_election'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->