<?php
/* @var $this StateController */
/* @var $model Town */
$this->breadcrumbs = array (
        ucfirst(strtolower($model->state->name)) => array (
                'state/view','id' => $model->state->id_state, 
        ),
        $model->name 
);

$this->menu = array (
        array (
                'label' => 'List State',
                'url' => array (
                        'index' 
                ) 
        ),
        array (
                'label' => 'Create State',
                'url' => array (
                        'create' 
                ) 
        ),
        array (
                'label' => 'Update State',
                'url' => array (
                        'update',
                        'id' => $model->id_state 
                ) 
        ),
        array (
                'label' => 'Delete State',
                'url' => '#',
                'linkOptions' => array (
                        'submit' => array (
                                'delete',
                                'id' => $model->id_state 
                        ),
                        'confirm' => 'Are you sure you want to delete this item?' 
                ) 
        ),
        array (
                'label' => 'Manage State',
                'url' => array (
                        'admin' 
                ) 
        ) 
);
?>

<h1 class="acname"><?= strtolower(__('{distname} District, {state}',['{distname}' => $model->name,'{state}' => $model->state->name])) ?></h1>

<?php
$officer = Officer::model()->findByAttributes([
        'fkey_place' => $model->id_district,
        'desig' => 'DISTCOLLECTOR'        
]);
if(isset($officer))
{
    echo $this->renderPartial('/site/_district',['data' => $officer]);
}

$poly = AssemblyPolygon::model ()->findAll (
        [
                'select' => 'acno',
                'condition' => 'dt_code=:dt_code and id_state=:state and polytype=:poly',
                'params' => [
                        'state' => $model->id_state,
                        'dt_code' => $model->id_place,
                        'poly' => 'WARD',
                ]
        ] );
echo '<div class="view">';
echo '<h2>' . __ ( 'Municipal Wards' ) . '</h2>';

echo '<ol>';
foreach ( $poly as $ac )
{
    if(empty($ac->pc_name_clean))
        continue;
    
    $attrs= ['name' => $ac->pc_name_clean,'ctype' => 'PARL','id_state' => $model->id_state];
    $consti = Constituency::model()->findByAttributes($attrs);
    echo CHtml::tag ( 'li', [ ],
            CHtml::link ( $consti->name,
                    [
                            'state/loksabha',
                            'id' => $consti->id_consti
                    ] ) );
}
echo '</ol></div>';

echo $this->renderPartial('_assemblies',['model' => $model]);
echo $this->renderPartial('_towns',['model' => $model]);
echo $this->renderPartial('_blocks',['model' => $model]);

$dist = $model;
$govdata ['dist_officer'] = Officer::model ()->with ( [ 
        'district' 
] )->together ()->findByAttributes ( [ 
        'fkey_place' => $dist->id_district,
        'desig' => Officer::DESIG_DEPUTYCOMMISSIONER 
] );
if (isset ( $dist->division ))
    $govdata ['div_officer'] = Officer::model ()->with ( [ 
            'district' 
    ] )->together ()->findByAttributes ( [ 
            'fkey_place' => $dist->division->id_district,
            'desig' => Officer::DESIG_DIVCOMMISSIONER 
    ] );
if (! empty ( $govdata ['dist_officer'] ))
    $this->renderPartial ( '//site/_district', [ 
            'data' => $govdata ['dist_officer'], 
    ] );

if (! empty ( $govdata ['div_officer'] ))
    $this->renderPartial ( '//site/_district', [ 
            'data' => $govdata ['div_officer'], 
    ] );
            

if(isset($model->district) && Yii::app()->user->checkAccess('ADD_DEPUTY_COMMISSIONER'))
{
    echo $this->renderPartial('_districteditor',['district' => $model->district]);        
}