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
                
    }
    else 
    {
        $con2 = Results2014::model ()->findByAttributes ( [
                'CONSTITUENCY' => $ass->PC_NAME
        ] );
        
        $con3 = TamilNaduResults2016::model ()->findByAttributes ( [
                'acno' => $ass->acno
        ] );
                
        $this->widget ( 'zii.widgets.CDetailView', array (
                'data' => $con3
        ) );
        
    }
    
    $this->widget ( 'zii.widgets.CDetailView', array (
            'data' => $con2
    ) );
    
}

/*
 * $con1 = Constituency::model()->findByAttributes(['eci_ref' =>
 * $ass->PC_NAME]);
 *
 * $this->widget('zii.widgets.CDetailView', array(
 * 'data'=>$con1,
 * ));
 */



?>