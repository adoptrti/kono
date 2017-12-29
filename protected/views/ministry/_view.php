<?php
/* @var $this MinistryController */
/* @var $data Ministry */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_ministry')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_ministry), array('view', 'id'=>$data->id_ministry)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />


</div>