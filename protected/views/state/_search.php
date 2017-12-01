<?php
/* @var $this StateController */
/* @var $model State */
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
		<?php echo $form->label($model,'ST_CODE'); ?>
		<?php echo $form->textField($model,'ST_CODE'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ias_short_code'); ?>
		<?php echo $form->textField($model,'ias_short_code',array('size'=>2,'maxlength'=>2)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'id_census'); ?>
		<?php echo $form->textField($model,'id_census'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'eci_ref'); ?>
		<?php echo $form->textField($model,'eci_ref',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'session_from'); ?>
		<?php echo $form->textField($model,'session_from'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'session_to'); ?>
		<?php echo $form->textField($model,'session_to'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lok_parl_seats'); ?>
		<?php echo $form->textField($model,'lok_parl_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'amly_seats'); ?>
		<?php echo $form->textField($model,'amly_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'raj_parl_seats'); ?>
		<?php echo $form->textField($model,'raj_parl_seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'iso3166'); ?>
		<?php echo $form->textField($model,'iso3166',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'psloc'); ?>
		<?php echo $form->textField($model,'psloc'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'eci_dist_count'); ?>
		<?php echo $form->textField($model,'eci_dist_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'eci_amly_count'); ?>
		<?php echo $form->textField($model,'eci_amly_count'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'slug'); ?>
		<?php echo $form->textField($model,'slug',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mcorp_count'); ?>
		<?php echo $form->textField($model,'mcorp_count'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->