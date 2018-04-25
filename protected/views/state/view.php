<?php
/* @var $this StateController */
/* @var $model State */

$this->breadcrumbs=array(
	'States'=>array('index'),
	ucwords(strtolower( $model->name)),
);

$this->menu=array(
	array('label'=>'List State', 'url'=>array('index')),
	array('label'=>'Create State', 'url'=>array('create')),
	array('label'=>'Update State', 'url'=>array('update', 'id'=>$model->id_state)),
	array('label'=>'Delete State', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_state),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage State', 'url'=>array('admin')),
);
?>

<h1><?php echo $model->name; ?></h1>

<?php /* $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_state',
		'ST_CODE',
		'name',
		'ias_short_code',
		'id_census',
		'eci_ref',
		'session_from',
		'session_to',
		'lok_parl_seats',
		'amly_seats',
		'raj_parl_seats',
		'updated',
		'iso3166',
		'psloc',
		'eci_dist_count',
		'eci_amly_count',
		'slug',
		'mcorp_count',
	),
)); */

$districts = Town::model()->findAll([
        'condition' => 'dt_code>0 and sdt_code=0 and tv_code=0 and id_state=?',
        'select' => 'distinct name,id_place',
        'order' => 'name',
        'params' => [$model->id_state]
]);

if(isset($model->chiefminister))
    echo $this->renderPartial('_chiefminister',['officer' => $model->chiefminister]);    

?><div class="view items">
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

<div class="view items amly">
<h2><?=__('State Assembly Constituencies') ?></h2>
<ol>
<?php 
foreach($model->amly_constituencies as $ac)
{
	if(empty($ac->eci_ref))
		continue;
		
	#fputs(STDERR,"ac name=" . $ac->name . ", id_state={$ac->id_state}\n");
    echo CHtml::tag('li',[],CHtml::link ( $ac->eci_ref . " " . $ac->name,
            [
                    'state/assembly',
                    'acno' => $ac->eci_ref,
                    'id_state' => $model->id_state
            ] ));
}
?>
</ol>
</div>

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
<?php

if(Yii::app()->user->checkAccess('ADD_CHIEF_MINISTER'))
{
    echo $this->renderPartial('_stateeditor',['state' => $model]);        
}