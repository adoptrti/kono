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

echo CHtml::tag('h1',[],$model->name);

if(isset($model->chiefminister))
    echo $this->renderPartial('_chiefminister',['officer' => $model->chiefminister]);    

$this->renderPartial("_view_dt",['model' => $model]);
$this->renderPartial("_view_as",['model' => $model]);
$this->renderPartial("_view_ls",['model' => $model]);
$this->renderPartial("_view_mc",['model' => $model]);
if(Yii::app()->user->checkAccess('ADD_CHIEF_MINISTER'))
{
    echo $this->renderPartial('_stateeditor',['state' => $model]);        
}