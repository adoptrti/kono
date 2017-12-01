<?php
/* @var $this AssemblyresultsController */
/* @var $data TamilNaduResults2016 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_result')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_result), array('view', 'id'=>$data->id_result)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_election')); ?>:</b>
	<?php echo CHtml::encode($data->id_election); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::encode($data->id_state); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_consti')); ?>:</b>
	<?php echo CHtml::encode($data->id_consti); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('acname')); ?>:</b>
	<?php echo CHtml::encode($data->acname); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('acno')); ?>:</b>
	<?php echo CHtml::encode($data->acno); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('gender')); ?>:</b>
	<?php echo CHtml::encode($data->gender); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('party')); ?>:</b>
	<?php echo CHtml::encode($data->party); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phones')); ?>:</b>
	<?php echo CHtml::encode($data->phones); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('emails')); ?>:</b>
	<?php echo CHtml::encode($data->emails); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ST_CODE')); ?>:</b>
	<?php echo CHtml::encode($data->ST_CODE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('picture')); ?>:</b>
	<?php echo CHtml::encode($data->picture); ?>
	<br />

	*/ ?>

</div>