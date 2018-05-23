<?php
/* @var $this StateController */
/* @var $district District */
?>
<div class="view">
<h2><?= __ ( 'Data Editor' )?></h2>

<ol>
<li><?=CHtml::link(__('Add Deputy Commissioner'),
['officer/create','id_state' => $district->id_state,'id_district' => $district->id_district ])?>
</ol>

</div>