<?php
/* @var $this AssemblyresultsController */
/* @var $model TamilNaduResults2016 */

$this->breadcrumbs=array(
	'Tamil Nadu Results2016s'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List TamilNaduResults2016', 'url'=>array('index')),
	array('label'=>'Create TamilNaduResults2016', 'url'=>array('create')),
	array('label'=>'Update TamilNaduResults2016', 'url'=>array('update', 'id'=>$model->id_result)),
	array('label'=>'Delete TamilNaduResults2016', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_result),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TamilNaduResults2016', 'url'=>array('admin')),
);
?>

<h1>View TamilNaduResults2016 #<?php echo $model->id_result; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id_result',
		'id_election',
		'id_state',
		'id_consti',
		'acname',
		'acno',
		'name',
		'gender',
		'party',
		'address',
		'phones',
		'emails',
		'ST_CODE',
		'picture',
	),
));

echo'<ol>';
foreach($model->committees as $comm)
	echo CHtml::tag('li',[],CHtml::link($comm->name,['/committee/view','id' => $comm->id_comm]));
echo'</ol>';

$this->renderPartial('//site/_assembly',['data' => $model,'full' => true]);
?>
