<?php
/* @var $this PanchayatController */
/* @var $data Panchayat */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_panchayat')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_panchayat), array('view', 'id'=>$data->id_panchayat)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_block')); ?>:</b>
	<?php echo CHtml::encode($data->id_block); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated')); ?>:</b>
	<?php echo CHtml::encode($data->updated); ?>
	<br />


</div>

<?php
if(!isset($detail))
    return;
?>
<div class="view">
<h2><?= __('Villages') ?></h2>
<ol>
<?php
foreach($data->villages as $village)
{
    echo CHtml::tag('li',[],$village->namelink);    
}
?>
</ol>
</div>
