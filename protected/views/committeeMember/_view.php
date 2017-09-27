<?php
/* @var $this CommitteeMemberController */
/* @var $data CommitteeMember */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_comm_member')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_comm_member), array('view', 'id'=>$data->id_comm_member)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_comm')); ?>:</b>
	<?php echo CHtml::encode($data->id_comm); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_result')); ?>:</b>
	<?php echo CHtml::encode($data->id_result); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('chairman')); ?>:</b>
	<?php echo CHtml::encode($data->chairman); ?>
	<br />


</div>