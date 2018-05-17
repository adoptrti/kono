<?php
/* @var $this StateController */
/* @var $state State */
?>
<div class="view">
<h2><?= __ ( 'Data Editor' )?></h2>

<ol>
<li><?php
    if(empty($state->chiefminister))
        echo CHtml::link(__('Add Chief Minister'),['officer/create','id_state' => $state->id_state,'desig' => Officer::DESIG_CHIEFMINISTER]);
    else
        echo CHtml::link(__('Edit Chief Minister'),['officer/update','id' => $state->chiefminister->id_officer]);
?>
<li><?=CHtml::link(__('Add Deputy Chief Minister'),['officer/create','id_state' => $state->id_state,'desig' => Officer::DESIG_DEPUTYCHIEFMINISTER])?>
<li><?php
    if(empty($state->governer))
        echo CHtml::link(__('Add Governer'),['officer/create','id_state' => $state->id_state,'desig' => Officer::DESIG_GOVERNER]);
    else
        echo CHtml::link(__('Edit Governer'),['officer/update','id' => $state->governer->id_officer]);
?>
</ol>

</div>