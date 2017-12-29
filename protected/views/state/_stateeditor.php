<?php
/* @var $this StateController */
/* @var $state State */
?>
<div class="view">
<h2><?= __ ( 'Data Editor' )?></h2>

<ol>
<li><?=CHtml::link(__('Add Chief Minister'),['officer/create','id_state' => $state->id_state,'desig' => Officer::DESIG_CHIEFMINISTER])?>
<li><?=CHtml::link(__('Add Deputy Chief Minister'),['officer/create','id_state' => $state->id_state,'desig' => Officer::DESIG_DEPUTYCHIEFMINISTER])?>
<li><?=CHtml::link(__('Add Governer'),['officer/create','id_state' => $state->id_state,'desig' => Officer::DESIG_GOVERNER])?>
</ol>

</div>