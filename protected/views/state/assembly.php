<?php
/* @var $this StateController */
/* @var $model Constituency */

$this->breadcrumbs=array(
    ucfirst(strtolower($model->state->name)) => array (
            'state/view','id' => $model->state->id_state,
    ),        
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

<h1><?php echo $this->pageTitle?></h1>

<?php

if(!isset($model->mla))
{
    //WHEN MLA DATA IS NOT FOUND
    $mla = new AssemblyResults();
    $mla->constituency = $model;
}
else
{
    $mla2 = AssemblyResults::model()->findByPk($model->mla->id_result);
    $mla = $mla2;
}
$this->renderPartial('//site/_assembly',['data' => $mla,'full' => true]);

if(isset($mla->constituency->ls_constituency->mp))
    $this->renderPartial ( '//site/_lowerhouse', [
            'data' => $mla->constituency->ls_constituency->mp,
            'poly' => false
    ] );

#Need to fix it, never tested. 
if (!empty($mla->constituency->acpoly->district))
{
    $this->renderPartial ( '//site/_district', [
            'data' => isset($mla->constituency->acpoly->district->officers[0]) ? $mla->constituency->acpoly->district->officers[0] : false,
            'district' => $mla->constituency->acpoly->district,
            //'rawdata' => $rawdata,
        ] );
}
        
?>