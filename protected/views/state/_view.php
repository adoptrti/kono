<?php
/* @var $this StateController */
/* @var $data State */
?>

<div class="view states">
	<?php echo CHtml::tag('h2',[],CHtml::link($data->name,['state/view','id' => $data->id_state])); ?>
	<?php
	if(isset($data->chiefminister)) 
	    $this->renderPartial('_chiefminister',['officer' => $data->chiefminister,'h3' => true]);
	?>
</div>