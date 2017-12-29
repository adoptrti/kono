<?php
/* @var $this MinisterController */
/* @var $model Minister */
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
		<?php echo $form->label($model,'id_member'); ?>
		<?php echo $form->textField($model,'id_member'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'appointed_from'); ?>
		<?php echo $form->textField($model,'appointed_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'appointed_to'); ?>
		<?php echo $form->textField($model,'appointed_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created'); ?>
		<?php echo $form->textField($model,'created'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_minister'); ?>
		<?php echo $form->textField($model,'id_minister'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_ministry'); ?>
		<?php echo $form->textField($model,'id_ministry'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->