<?php
/* @var $this CommitteeController */
/* @var $model Committee */

$this->breadcrumbs=array(
	'Committees'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Committee', 'url'=>array('index')),
	array('label'=>'Create Committee', 'url'=>array('create')),
	array('label'=>'Update Committee', 'url'=>array('update', 'id'=>$model->id_comm)),
	array('label'=>'Delete Committee', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_comm),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Committee', 'url'=>array('admin')),
);
?>

<h1>View Committee #<?php echo $model->id_comm; ?></h1>

<?php
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_state',
		'id_consti',
		'ctype',
		'name',
		'id_comm',
		'id_election',
	),
));

echo'<ol>';

foreach($model->members as $mem)
	echo CHtml::tag('li',[],CHtml::link($mem->name,['/assemblyresults/view','id' => $mem->id_result]));

echo'</ol>';
?>
