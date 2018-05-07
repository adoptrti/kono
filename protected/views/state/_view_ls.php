<div class="view items amly">
<h2><?=__('Lok Sabha Constituencies') ?></h2>
<ol>
<?php 
foreach($model->parl_constituencies as $pc)
{
    echo CHtml::tag('li',[],CHtml::link ( $pc->name,
            [
                    'state/loksabha',
                    'id' => $pc->id_consti
            ]));
}
?>
</ol>
</div>
