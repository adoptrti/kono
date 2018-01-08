<?php
$poly = AssemblyPolygon::model ()->findAll (
        [
                'condition' => 'dist_name=:dist and id_state=:state and polytype=:ptype',
                'params' => [
                        'state' => $model->id_state,
                        'ptype' => 'AC',
                        'dist' => $model->dt_name
                ]
        ] );

echo '<div class="view">';
echo '<h2>' . __ ( 'Legislative Assemblies' ) . '</h2>';

echo '<ol>';
foreach ( $poly as $ac )
{
    echo CHtml::tag ( 'li', [ ],
            CHtml::link ( $ac->name,
                    [
                            'state/assembly',
                            'acno' => $ac->acno,
                            'id_state' => $ac->id_state
                    ] ) );
}
echo '</ol></div>';