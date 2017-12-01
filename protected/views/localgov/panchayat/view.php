<?php
/* @var $this PanchayatController */
/* @var $model Panchayat */

$this->breadcrumbs = [ ];

if ($model->block->district->state)
    $this->breadcrumbs [$model->block->district->state->name] = [ 
            'state/view',
            'id' => $model->block->district->state->id 
    ];

if ($model->block->district)
    $this->breadcrumbs [$model->block->district->name] = [ 
            '/localgov/district/view',
            'id' => $model->block->district->id 
    ];

if ($model->block)
    $this->breadcrumbs [$model->block->name] = [ 
            '/localgov/block/view',
            'id' => $model->block->id 
    ];

$this->breadcrumbs [] = $model->name;
        
$this->menu=array(
	array('label'=>'List Panchayat', 'url'=>array('index')),
	array('label'=>'Create Panchayat', 'url'=>array('create')),
	array('label'=>'Update Panchayat', 'url'=>array('update', 'id'=>$model->id_panchayat)),
	array('label'=>'Delete Panchayat', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id_panchayat),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Panchayat', 'url'=>array('admin')),
);
?>

<h1>View Panchayat #<?php echo $model->id_panchayat; ?></h1>

<?php $this->renderPartial('_view',['data' => $model,'detail' => true]); ?>