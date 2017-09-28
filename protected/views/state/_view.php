<?php
/* @var $this StateController */
/* @var $data State */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_state')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_state), array('view', 'id'=>$data->id_state)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ST_CODE')); ?>:</b>
	<?php echo CHtml::encode($data->ST_CODE); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ias_short_code')); ?>:</b>
	<?php echo CHtml::encode($data->ias_short_code); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_census')); ?>:</b>
	<?php echo CHtml::encode($data->id_census); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('eci_ref')); ?>:</b>
	<?php echo CHtml::encode($data->eci_ref); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('session_from')); ?>:</b>
	<?php echo CHtml::encode($data->session_from); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('session_to')); ?>:</b>
	<?php echo CHtml::encode($data->session_to); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('lok_parl_seats')); ?>:</b>
	<?php echo CHtml::encode($data->lok_parl_seats); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amly_seats')); ?>:</b>
	<?php echo CHtml::encode($data->amly_seats); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('raj_parl_seats')); ?>:</b>
	<?php echo CHtml::encode($data->raj_parl_seats); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iso3166')); ?>:</b>
	<?php echo CHtml::encode($data->iso3166); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('psloc')); ?>:</b>
	<?php echo CHtml::encode($data->psloc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('eci_dist_count')); ?>:</b>
	<?php echo CHtml::encode($data->eci_dist_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('eci_amly_count')); ?>:</b>
	<?php echo CHtml::encode($data->eci_amly_count); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('slug')); ?>:</b>
	<?php echo CHtml::encode($data->slug); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('mcorp_count')); ?>:</b>
	<?php echo CHtml::encode($data->mcorp_count); ?>
	<br />

	*/ ?>

</div>