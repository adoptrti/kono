<?php
/* @var $this DistrictController */
/* @var $model District */

$this->breadcrumbs=array(
	'Districts'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List District', 'url'=>array('index')),
	array('label'=>'Create District', 'url'=>array('create')),
    array('label'=>'Create Division', 'url'=>array('creatediv','id_state' => $model->id_state)),
	array('label'=>'Update District', 'url'=>array('update', 'id'=>$model->id_district)),
	array('label'=>'Delete District', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_district),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage District', 'url'=>array('admin')),
);
?>

<h1>View <?=count($model->districts) ? __('Division') : __('District')?> #<?php echo $model->id_district; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_district',
		'name',
		'updated',
		'id_state',
	),
)); 

if(count($model->districts))
{
    echo '<h2>' . __('Member Districts') . '</h2>';
    echo '<ol>';
    foreach($model->districts as $dist)
        echo CHtml::tag('li',[],CHtml::link($dist->name,['view','id' => $dist->id_district]));
    echo '</ol>';
}
?>
