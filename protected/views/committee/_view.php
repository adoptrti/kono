<?php
/* @var $this CommitteeController */
/* @var $data Committee */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_comm')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_comm), array('view', 'id'=>$data->id_comm)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::encode($data->id_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_consti')); ?>:</b>
	<?php echo CHtml::encode($data->id_consti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ctype')); ?>:</b>
	<?php echo CHtml::encode($data->ctype); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_election')); ?>:</b>
	<?php echo CHtml::encode($data->id_election); ?>
	<br />


</div>