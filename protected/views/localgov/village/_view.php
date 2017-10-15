<?php
/* @var $this VillageController */
/* @var $data LBVillage */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_village')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_village), array('view', 'id'=>$data->id_village)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_panchayat')); ?>:</b>
	<?php echo CHtml::encode($data->id_panchayat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />


</div>