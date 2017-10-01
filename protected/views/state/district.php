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
$poly = AssemblyPolygon::model ()->findAll (
        [
                'select' => 'pcno,pc_name_clean',
                'group' => 'pcno,pc_name_clean',
                'condition' => 'dist_name=:dist and id_state=:state',
                'params' => [
                        'state' => $model->id_state,
                        'dist' => $model->dt_name
                ]
        ] );

echo '<h2>' . __ ( 'Lok Sabha Parliamentary Constituency' ) . '</h2>';

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
echo '</ol>';

$poly = AssemblyPolygon::model ()->findAll ( 
        [ 
                'condition' => 'dist_name=:dist and id_state=:state and polytype=:ptype',
                'params' => [ 
                        'state' => $model->id_state,
                        'ptype' => 'AC',
                        'dist' => $model->dt_name 
                ] 
        ] );

echo '<h2>' . __ ( 'Legislative Assemblies' ) . '</h2>';

echo '<ol>';
foreach ( $poly as $ac )
{
    echo CHtml::tag ( 'li', [ ], 
            CHtml::link ( $ac->AC_NAME, 
                    [ 
                            'state/assembly',
                            'acno' => $ac->acno,
                            'id_state' => $ac->id_state 
                    ] ) );
}
echo '</ol>';

echo '<h2>' . __ ( 'Towns' ) . '</h2>';
$towns = Town::model ()->findAll ( 
        [ 
                'condition' => 'dt_name = :dtname and id_state=:state and tv_code>0',
                'params' => [ 
                        'dtname' => $model->dt_name,
                        'state' => $model->id_state 
                ] 
        ] );
$tvtypename = [ 
        "cb" => __ ( "Cantonment Board" ),
        "cmc" => __ ( "City Municipal Council" ),
        "cmc+og" => __ ( "City Municipal Council" ),
        "gp" => __ ( "Gram Panchayat" ),
        "gp+og" => __ ( "Gram Panchayat" ),
        "ina" => __ ( "Industrial Notified Area" ),
        "its" => __ ( "Industrial Township" ),
        "its+og" => __ ( "Industrial Township" ),
        "m" => __ ( "Municipality" ),
        "m+og" => __ ( "Municipality" ),
        "mb" => __ ( "Municipal Board" ),
        "mb+og" => __ ( "Municipal Board" ),
        "mc" => __ ( "Municipal Committee" ),
        "mc+og" => __ ( "Municipal Committee" ),
        "mci" => __ ( "mci" ),
        "mci+og" => __ ( "mci+og" ),
        "mcl" => __ ( "Municipal Council" ),
        "mcl+og" => __ ( "Municipal Council" ),
        'mcorp' => __ ( 'Municipal Corporation' ),
        "mcorp+og" => __ ( 'Municipal Corporation' ),
        "na" => __ ( "Notified Area" ),
        "na+og" => __ ( "Notified Area" ),
        "nac" => __ ( "Notified Area Committee" ),
        "nac+og" => __ ( "Notified Area Committee" ),
        'np' => __ ( 'Nagar Parishad' ),
        "np+og" => __ ( 'Nagar Parishad' ),
        "npp" => __ ( "Nagar Palika Parishad" ),
        "npp+og" => __ ( "Nagar Palika Parishad" ),
        "nt" => __ ( "Notified Town" ),
        "st" => __ ( "Small Town Committee" ),
        "tc" => __ ( "Town Committee" ),
        "tmc" => __ ( "Town Municipal Council" ),
        "tmc+og" => __ ( "Town Municipal Council" ),
        "tp" => __ ( "Town Panchayat" ),
        "tp+og" => __ ( "Town Panchayat" ) 
];

$tvtypegroup = [ 
        "cb" => 3,
        "cmc" => 3,
        "cmc+og" => 3,
        "gp" => 4,
        "gp+og" => 4,
        "ina" => 3,
        "its" => 3,
        "its+og" => 3,
        "m" => 2,
        "m+og" => 2,
        "mb" => 3,
        "mb+og" => 3,
        "mc" => 3,
        "mc+og" => 3,
        "mci" => 2,
        "mci+og" => 2,
        "mcl" => 2,
        "mcl+og" => 2,
        'mcorp' => 1,
        "mcorp+og" => 1,
        "na" => 3,
        "na+og" => 3,
        "nac" => 3,
        "nac+og" => 3,
        'np' => 3,
        "np+og" => 3,
        "npp" => 2,
        "npp+og" => 2,
        "nt" => 3,
        "st" => 3,
        "tc" => 3,
        "tmc" => 3,
        "tmc+og" => 3,
        "tp" => 3,
        "tp+og" => 3 
];
asort ( $tvtypegroup );
$tvtypegroup2 = $tvtypegroup;
$types = [ ];
echo '<ol>';
foreach ( $towns as $town )
{
    $types [$town->tvtype] [] = $town;
}

foreach ( $tvtypegroup2 as $grp => $data )
{
    if (empty ( $types [$grp] ))
        continue;
    
    echo '<h3>' . $tvtypename [$grp] . '</h3><ol>';
    
    foreach ( $types [$grp] as $town )
    {
        echo CHtml::tag ( 'li', [ ], 
                CHtml::link ( $town->name, 
                        [ 
                                'state/town',
                                'id_place' => $town->id_place 
                        ] ) );
    }
    echo '</ol>';
}
echo '</ol>';