<?php
//10.856975354531784,77.1036683713969
//select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ImportKMLCommand extends CConsoleCommand
{

    public function actionIndex($file)
    {
        /*print_r($_GET);
        print_r($_SERVER);
        die;*/
        $dom = new DOMDocument ();
        $file = realpath ( $file );
        if ($dom->load ( $file ) == false)
            die ( "could not load file :$file" );
        echo "Loaded file $file\n";
        $xpath = new DOMXpath ( $dom );
        
        $ele = $dom->getElementsByTagName ( 'Placemark' );
        
        foreach ( $ele as $place )
        {
            parseplace ( $place );
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

function parseplace($place)
{
    $ExtendedData = parseExtendedData ( $place );
    $coords = parseManyCoords( $place );
    /*
     * $nodes = $place->childNodes;
     * foreach ($nodes as $node) {
     * switch($node->nodeName)
     * {
     * case 'ExtendedData':
     * $ExtendedData = parseExtendedData($node);
     * break;
     * case 'Polygon':
     * $coords = parseCoords($node);
     * break;
     * }
     * insertRow($ExtendedData,$coords);
     * }
     */
    insertRow ( $ExtendedData, $coords );
}

function insertRow($ExtendedData, $many_coords)
{
    $poly = new AssemblyPolygon();
    
    $polystring = [];
    foreach($many_coords as $coords)
    {
        $polystring[] = '(' . implode(',' , array_reduce($coords,function($carry,$item) 
        {
            if(empty($item[0]))
                return $carry;
            
            $carry[] = $item[0] . ' ' . $item[1];
            return $carry;
                
        })) . ')';
    }
    print_r($ExtendedData);
    
    setprops($poly, $ExtendedData,'dt_code', 'DT_CODE');
    setprops($poly, $ExtendedData,'st_code', 'ST_CODE');
    setprops($poly, $ExtendedData,'st_name', 'ST_NAME');
    setprops($poly, $ExtendedData,'dist_name', 'DIST_NAME');
    setprops($poly, $ExtendedData,'name', 'AC_NAME');
    setprops($poly, $ExtendedData,'pcno', 'PC_NO');
    setprops($poly, $ExtendedData,'pc_name', 'PC_NAME');
    setprops($poly, $ExtendedData,'pc_id', 'PC_ID');
    setprops($poly, $ExtendedData,'Shape_Leng', 'Shape_Leng');
    setprops($poly, $ExtendedData,'Shape_Area', 'Shape_Area');
    setprops($poly, $ExtendedData,'MaxSimpTol', 'MaxSimpTol');
    setprops($poly, $ExtendedData,'MinSimpTol', 'MinSimpTol');
    
    $poly->acno = $ExtendedData['AC_NO'];
    
    $poly->poly = new CDbExpression("ST_PolygonFromText('POLYGON(" . implode(',',$polystring) . ")')");
    
    if(empty($poly->acno))
    {
        echo "Invalid Data found. Ignoring\n";
        return;
    }
    if(!$poly->save())
        print_r($poly->errors);
    echo $poly->acno . "\n";
}

function setprops($poly, $ExtendedData, $setter, $field)
{
    if(!empty($ExtendedData[$field]))
        $poly->$setter = $ExtendedData[$field];
}

function parseExtendedData($node)
{
    $rt = [ ];
    $eles = $node->getElementsByTagName ( 'SimpleData' );
    foreach ( $eles as $ele )
    {
        $rt [$ele->getAttribute ( 'name' )] = $ele->nodeValue;
    }
    
    return $rt;
}

function parseCoords($ele)
{
    $c2 = array_map ( 
            function ($item)
            {
                $a = explode ( ',', $item );
                if (count ( $a ) == 3)
                    return $a;
            }, explode ( ' ', trim($ele->nodeValue) ) );   
    return $c2;
}

function parseManyCoords($node)
{
    $c3=[];
    $eles = $node->getElementsByTagName ( 'coordinates' );
    
    if ($eles->length == 0)
        die ( "Not found coords\n" );
    
    foreach($eles as $ele)
        $c3[] = parseCoords($ele);
    
    return $c3;
}