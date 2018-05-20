<?php
/* @var $this OfficerController */
/* @var $data Officer */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_officer')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_officer), array('view', 'id'=>$data->id_officer)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('fkey_place')); ?>:</b>
	<?php
	$desig = $data->desig;
	if($desig == Officer::DESIG_CHIEFMINISTER || $desig == Officer::DESIG_DEPUTYCHIEFMINISTER || $desig == Officer::DESIG_GOVERNER || $desig == Officer::DESIG_LGOVERNER)
	    echo $data->state->namelink;
    else if(isset($data->district->namelink))
	    echo $data->district->namelink; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('desig')); ?>:</b>
	<?php echo CHtml::encode($data->desig); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created')); ?>:</b>
	<?php echo CHtml::encode($data->created); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('picture')); ?>:</b>
	<?php echo CHtml::encode($data->picture); ?>
	<?php echo CHtml::image("/images/" . $data->picture); ?>
	<br />
	
	
	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('fax')); ?>:</b>
	<?php echo CHtml::encode($data->fax); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	*/ ?>

</div>