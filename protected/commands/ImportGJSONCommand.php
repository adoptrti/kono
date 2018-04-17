<?php
//10.856975354531784,77.1036683713969
//select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ImportGJSONCommand extends CConsoleCommand
{

    public function actionIndex($file)
    {
        /*print_r($_GET);
        print_r($_SERVER);
        die;*/
        $file = realpath ( $file );
        
        echo "Loaded file $file\n";
        $data = json_decode(file_get_contents($file));
        
        $pctr= 1;
        
        foreach ( $data->features as $place )
        {
            parseplace ( $place,$pctr++);
        }
        
        $rs = AssemblyPolygon::model()->findAll([
                'group' => 'ST_NAME',
                'select' => 'ST_NAME,count(*) as ctr1',
        ]);
        
        foreach($rs as $r)
        {
            echo "{$r->st_name}\t{$r->ctr1}\n";
        }
    }
}

function parseplace($place,$pctr)
{
    $ExtendedData = parseExtendedData ( $place );
    $coords = parseManyCoords( $place );
    insertRow ( $ExtendedData, $coords ,$pctr);
}

#modified now for importing bangalore wards
function insertRow($ExtendedData, $many_coords,$pctr)
{
    $poly = new AssemblyPolygon();
    #print_r($many_coords);
    $polystring = [];
    foreach($many_coords[0] as $coords)
    {
        #$coords is an array or array, which is reduced into 
        #1 array of strings imploded into 1 comma seperated string.
        $polystring[] = '(' . implode(',' , array_reduce($coords,function($carry,$item) 
        {
            if(empty($item[0]))
                return $carry;
            
            $carry[] = $item[0] . ' ' . $item[1];
            return $carry;
                
        })) . ')';
    }
    
    print_r($ExtendedData);
    
    setprops($poly, $ExtendedData,'acno', 'WARD_NO');
    setprops($poly, $ExtendedData,'name', 'WARD_NAME');
    
    #setprops($poly, $ExtendedData,'zone', 'Zone_No');
    setprops($poly, $ExtendedData,'Shape_Area', 'AREA_SQ_KM');
    #setprops($poly, $ExtendedData,'Shape_Leng', 'PERIMETER');

    $poly->poly = new CDbExpression("ST_PolygonFromText('POLYGON(" . implode(',',$polystring) . ")')");
    //very important
    $poly->polytype = 'WARD';
    $poly->dist_name = 'BANGALORE URBAN';
    $poly->dt_code = 67;
    $poly->st_code = 29; //diff from id_state
    $poly->id_state = 18;
    #$poly->ST_NAME = '';
    
    if(empty($poly->acno))
    {
        echo "Invalid Data found. Ignoring\n";
        return;
    }
    
    if(!$poly->save())
    {
        print_r($poly->errors);
        die('died saving ward:' . $poly->wardno);
    }
    
    addWard($poly->dist_name,$poly->acno,$poly->dt_code,$poly->st_code);
    
    echo $poly->acno . " (# $pctr) \n\n";
}

function addWard($city,$wardno,$dt_code,$st_code)
{
    $MR = MunicipalResults::model()->findByAttributes(['city' => $city,'wardno' => $wardno]);
    if(!$MR)
    {
        $MR = new MunicipalResults();
        $MR->wardno = $wardno;
        $MR->city = $city;
        $MR->id_city = 9819; //bangalore towns2011
        $MR->dt_code = $dt_code;
        $MR->st_code = $st_code;
        if(!$MR->save())
        {
            print_r($MR->errors);
            die('died saving ' . $wardno);
        }
    }
}

function setprops($poly, $ExtendedData, $setter, $field)
{
    if(!empty($ExtendedData->$field))
        $poly->$setter = $ExtendedData->$field;
}

function parseExtendedData($node)
{
    return $node->properties;
}

/*function parseCoords($ele)
{
    $c2 = array_map ( 
            function ($item)
            {
                $a = explode ( ',', $item );
                if (count ( $a ) == 3)
                    return $a;
            }, explode ( ' ', trim($ele->nodeValue) ) );   
    return $c2;
}*/

function parseManyCoords($node)
{    
    foreach($node->geometry->coordinates as $ele)
        $c3[] = $ele;
    
    return $c3;
}