<?php
/* @var $data array */

// @see https://dev.mysql.com/doc/refman/5.7/en/populating-spatial-columns.html
// @see
// https://stackoverflow.com/questions/15662910/search-a-table-for-point-in-polygon-using-mysql
#
#
#
$src = [ 
        'condition' => (new CDbExpression ( "ST_Contains(poly, GeomFromText(:point))")) . ' and state.name=:statename',
        'with' => ['state'],
        'params' => [ 
            ':statename' => $data0[0]->state,
            ':point' => 'POINT(' . $long . ' ' . $lat . ')' 
        ] 
];

$ass2 = AssemblyPolygon::model ()->findAll ( $src );

$data = [ ];
/* @var $ass AssemblyPolygon */
foreach ( $ass2 as $ass )
{    
    error_log('GIS-found poly:' . $ass->id_poly . ' name:' . $ass->name . ' type:' . $ass->polytype . ' id_village:' . $ass->id_village);
    if ($ass->polytype == 'WARD')
    {
        $con2 = MunicipalResults::model ()->findByAttributes ( [ 
                'wardno' => $ass->acno,
                'dt_code' => $ass->dt_code,
        ] );
        
        if ($con2)
            $data ['ward'] = $con2;
    }
    else if($ass->polytype == 'AC')
    {
        $data ['assembly'] = null;
        $data ['amly_poly'] = $ass;
        $con2 = LokSabha2014::model ()->findByAttributes ( [ 
                'pc_name_clean' => $ass->pc_name_clean 
        ] );
        
        if ($con2)
        {
            $data ['mp'] = $con2;
            $data ['mp_poly'] = $ass;
        }
        $att44 = [
                'acno' => $ass->acno ,
                'id_state' => $ass->id_state,
        ];        
        
        $con3 = AssemblyResults::model ()->findByAttributes ( $att44 );
        if ($con3)
        {
            $data ['assembly'] = $con3;
        }
    }
    else if($ass->polytype == 'VILLAGE')
    {
        $data['village'] = $ass;
    }
}

$dist = false;

//find district collector
if (isset ( $data ['amly_poly'] ))
{
    $dist = District::model ()->findByAttributes ( 
            [ 
                    'name' => $data ['amly_poly']->dist_name,
                    'id_state' => $data ['amly_poly']->id_state
            ] );
    
    if ($dist)
    {
        $data['dist_officer'] = Officer::model()->with(['district'])->together()->findByAttributes(['fkey_place' => $dist->id_district]);
    }
}   
else if (isset ( $data['village']->village))
{
    $data['dist_officer'] = Officer::model()->with(['district'])->together()->findByAttributes(['fkey_place' => $data['village']->village->panchayat->block->id_district]);
}

$this->renderPartial ( '_address', [ 
        'address' => $data0,
        'mp_poly' => $data ['mp_poly'],
        'w3w' => $w3w,
        'amly_poly' => $data ['amly_poly'],
        'data' => !empty($data ['ward']) ? $data ['ward'] : [],
] );

if (! empty ( $data['dist_officer']))
    $this->renderPartial ( '_district', [
            'data' => $data ['dist_officer'],
            'data0' => $data0,
    ] );
    

if (! empty ( $data ['ward'] ))
    $this->renderPartial ( '_ward', [ 
            'data' => $data ['ward'],
            'data0' => $data0,
    ] );

if (! empty ( $data ['village'] ))
        $this->renderPartial ( '_village', [
                'data' => $data ['village'],
                'data0' => $data0,
        ] );
        
if (! empty ( $data ['assembly'] ) || ! empty ( $data ['amly_poly'] )) 
    $this->renderPartial ( '_assembly', [ 
            'data' => $data ['assembly'],
            'poly' => $data ['amly_poly'] 
    ] );

if (! empty ( $data ['mp'] ))
    $this->renderPartial ( '_lowerhouse', [ 
            'data' => $data ['mp'],
            'poly' => $data ['mp_poly'] 
    ] );
#echo '<pre>' . print_r($data0,true) . print_r($att44) . '</pre>';
?>
