<?php
/* @var $this MinisterController */
/* @var $data Minister */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_minister')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_minister), array('view', 'id'=>$data->id_minister)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::encode($data->id_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_member')); ?>:</b>
	<?php echo CHtml::encode($data->id_member); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appointed_from')); ?>:</b>
	<?php echo CHtml::encode($data->appointed_from); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('appointed_to')); ?>:</b>
	<?php echo CHtml::encode($data->appointed_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('id_ministry')); ?>:</b>
	<?php echo CHtml::encode($data->id_ministry); ?>
	<br />

	*/ ?>

</div>