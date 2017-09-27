<?php
/* @var $this ElectionController */
/* @var $data Election */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_election')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_election), array('view', 'id'=>$data->id_election)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::encode($data->id_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('edate')); ?>:</b>
	<?php echo CHtml::encode($data->edate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('year')); ?>:</b>
	<?php echo CHtml::encode($data->year); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::encode($data->type); ?>
	<br />


</div>