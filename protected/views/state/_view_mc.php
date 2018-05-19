<div class="view items amly">
<h2><?=__('Municipal Corporation') ?></h2>
<ol>
<?php 
foreach($model->mc_constituencies as $mc)
{
    echo CHtml::tag('li',[],CHtml::link ( $mc->name,
            [
                    'state/town',
                    'id_place' => $mc->id_place
            ]));
}
?>
</ol>
</div>
