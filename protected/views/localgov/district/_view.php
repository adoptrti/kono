<?php
/* @var $this DistrictController */
/* @var $data District */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_district')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_district), array('view', 'id'=>$data->id_district)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::encode($data->id_state); ?>
	<br />


</div>