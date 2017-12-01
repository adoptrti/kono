<?php
/* @var $this BlockController */
/* @var $data Block */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_block')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id_block), array('view', 'id'=>$data->id_block)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_district')); ?>:</b>
	<?php echo CHtml::encode($data->id_district); ?>
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
<h2><?= __('Panchayats') ?></h2>
<ol>
<?php
foreach($data->panchayats as $panchayat)
{
    echo CHtml::tag('li',[],$panchayat->namelink);    
}
?>
</ol>
</div>