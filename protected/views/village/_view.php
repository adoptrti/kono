<?php
/* @var $this VillageController */
/* @var $data Village */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_village')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_village), array('view', 'id'=>$data->id_village)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::encode($data->id_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_district')); ?>:</b>
	<?php echo CHtml::encode($data->id_district); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('block')); ?>:</b>
	<?php echo CHtml::encode($data->block); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('panchayat')); ?>:</b>
	<?php echo CHtml::encode($data->panchayat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('village')); ?>:</b>
	<?php echo CHtml::encode($data->village); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />


</div>