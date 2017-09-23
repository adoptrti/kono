<?php
/* @var $data array */

// @see https://dev.mysql.com/doc/refman/5.7/en/populating-spatial-columns.html
// @see
// https://stackoverflow.com/questions/15662910/search-a-table-for-point-in-polygon-using-mysql

$src = [ 
        'condition' => (new CDbExpression ( "ST_Contains(poly, GeomFromText(:point))")) . ' and states.name=:statename',
        'with' => ['states'],
        'params' => [ 
            ':statename' => $data0[0]->state,
            ':point' => 'POINT(' . $long . ' ' . $lat . ')' 
        ] 
];

$ass2 = AssemblyPolygon::model ()->findAll ( $src );

$data = [ ];

foreach ( $ass2 as $ass )
{    
    if ($ass->polytype == 'WARD')
    {
        $con2 = MunicipalResults::model ()->findByAttributes ( [ 
                'wardno' => $ass->acno 
        ] );
        
        if ($con2)
            $data ['ward'] = $con2;
    }
    else
    {
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
        
        $con3 = TamilNaduResults2016::model ()->findByAttributes ( $att44 );
        if ($con3)
        {
            $data ['assembly'] = $con3;
            $data ['amly_poly'] = $ass;
        }
    }
}

$this->renderPartial ( '_address', [ 
        'address' => $data0,
        'mp_poly' => $data ['mp_poly'],
        'amly_poly' => $data ['amly_poly'],
        'data' => !empty($data ['ward']) ? $data ['ward'] : [],
] );

if (! empty ( $data ['ward'] ))
    $this->renderPartial ( '_ward', [ 
            'data' => $data ['ward'] 
    ] );

if (! empty ( $data ['assembly'] ))
    $this->renderPartial ( '_assembly', [ 
            'data' => $data ['assembly'],
            'poly' => $data ['amly_poly'] 
    ] );

if (! empty ( $data ['mp'] ))
    $this->renderPartial ( '_lowerhouse', [ 
            'data' => $data ['mp'],
            'poly' => $data ['mp_poly'] 
    ] );
echo '<pre>' . print_r($data0,true) . print_r($att44) . '</pre>';
?>