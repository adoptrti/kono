<?php
/* @var $this StateController */
/* @var $model State */

$this->breadcrumbs=array(
	'States'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List State', 'url'=>array('index')),
	array('label'=>'Create State', 'url'=>array('create')),
	array('label'=>'Update State', 'url'=>array('update', 'id'=>$model->id_state)),
	array('label'=>'Delete State', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_state),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage State', 'url'=>array('admin')),
);
?>

<h1>View State #<?php echo $model->id_state; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
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
));

$districts = Town::model()->findAll([
        'condition' => 'dt_code>0 and sdt_code=0 and tv_code=0 and id_state=?',
        'select' => 'distinct name,id_place',
        'params' => [$model->id_state]
]);
echo '<h2>' . __('Districts') . '</h2>';
echo '<ol>';
foreach($districts as $district)
{
    echo CHtml::tag('li',[],CHtml::link($district->name,['state/district','id' => $district->id_place]));
}
echo '</ol>';
