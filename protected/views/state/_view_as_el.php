<div class="view items amly">
<h2><?=__('Assembly Constituencies with Candidate details, Symbols and EVM Positions') ?></h2>
<ol>
<?php 
foreach($model->amly_constituencies as $ac)
{
	if(empty($ac->eci_ref))
		continue;
		
	#fputs(STDERR,"ac name=" . $ac->name . ", id_state={$ac->id_state}\n");
    echo CHtml::tag('li',[],CHtml::link ( $ac->eci_ref . " " . $ac->name,
            [
                    'election/candidates',
                    'eci_ref' => $ac->eci_ref,
                    'id_election' => $election->id_election,
                    //'id_state' => $model->id_state
            ] ));
}
?>
</ol>
</div>
