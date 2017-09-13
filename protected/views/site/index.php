<?php
// @see https://dev.mysql.com/doc/refman/5.7/en/populating-spatial-columns.html
// @see
// https://stackoverflow.com/questions/15662910/search-a-table-for-point-in-polygon-using-mysql
$src = [ 
        'condition' => new CDbExpression ( "ST_Contains(poly, GeomFromText(:point))" ),
        'params' => [ 
                ':point' => 'POINT(' . $data [1]->longitude . ' ' . $data [1]->latitude . ')' 
        ] 
];
$ass2 = AssemblyPolygon::model ()->findAll ( $src );

$data = [];

foreach ( $ass2 as $ass )
{
    $this->widget ( 'zii.widgets.CDetailView', [ 
            'data' => $ass 
    ] );
    
    if($ass->polytype == 'WARD')
    {
        $con2 = MunicipalResults::model ()->findByAttributes ( [
                'wardno' => $ass->acno
        ] );
        
        if($con2)
            $data['ward'] = $con2;
    }
    else 
    {
        $con2 = Results2014::model ()->findByAttributes ( [
                'CONSTITUENCY' => $ass->PC_NAME
        ] );
        
        if($con2)
        {
            $data['mp'] = $con2;
            $data['mp_poly'] = $ass;
        }                    
        
        $con3 = TamilNaduResults2016::model ()->findByAttributes ( [
                'acno' => $ass->acno
        ] );
        if($con3)
        {
            $data['assembly'] = $con3;
            $data['amly_poly'] = $ass;
        }
        
    }
        
}

if(!empty($data['ward']))
    $this->renderPartial('_ward',['data' => $data['ward']]);

if(!empty($data['assembly']))
    $this->renderPartial('_assembly',['data' => $data['assembly'],'poly' => $data['amly_poly']]);
/*    
if(!empty($data['mp']))
    $this->renderPartial('_ward',['data' => $data['mp']]);
*/
?>