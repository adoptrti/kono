<?php
/* @var $this BlockController */
/* @var $model Block */

$this->breadcrumbs = [ ];

if ($model->district->state)
    $this->breadcrumbs [$model->district->state->name] = [ 
            'state/view',
            'id' => $model->district->state->id 
    ];

if ($model->district)
    $this->breadcrumbs [$model->district->name] = [ 
            '/localgov/district/view',
            'id' => $model->district->id 
    ];

$this->breadcrumbs [] = $model->name;
        
$this->menu=array(
	array('label'=>'List Block', 'url'=>array('index')),
	array('label'=>'Create Block', 'url'=>array('create')),
	array('label'=>'Update Block', 'url'=>array('update', 'id'=>$model->id_block)),
	array('label'=>'Delete Block', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_block),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Block', 'url'=>array('admin')),
);
?>

<h1>View Block #<?php echo $model->id_block; ?></h1>

<?php $this->renderPartial('_view',['data' => $model,'detail' => true]); ?>
