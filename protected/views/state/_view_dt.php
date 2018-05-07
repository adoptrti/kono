<?php 
$districts = Town::model()->findAll([
		'condition' => 'dt_code>0 and sdt_code=0 and tv_code=0 and id_state=?',
		'select' => 'distinct name,id_place',
		'order' => 'name',
		'params' => [$model->id_state]
]);
?>
<div class="view items">
<h2><?=__('Districts') ?></h2>
<ol>
<?php 
foreach($districts as $district)
{
    echo CHtml::tag('li',[],CHtml::link($district->name,['state/district','id' => $district->id_place]));
}
?>
</ol>
</div>
