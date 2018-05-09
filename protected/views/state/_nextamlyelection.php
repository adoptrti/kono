<?php
/* @var $this SiteController */
/* @var $data AssemblyResult */

$myneta_map = [];     
?>
<div class="view amly">
    <h2 class="acname"><?= CHtml::link(__('{state} Assembly Elections {eyear}',
            [
            	'{eyear}' => date('Y',strtotime($election->edate)),
            	'{state}' => $election->state->name,
            ]),
            [
                'state/election',
            		'id_election' => $election->id_election,
            ])?></h2>
</div>