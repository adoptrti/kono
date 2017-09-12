<?php
//10.856975354531784,77.1036683713969
//select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ImportKMLCommand extends CConsoleCommand
{

    public function actionIndex($file)
    {
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
    
    $poly->DT_CODE= $ExtendedData['DT_CODE'];
    $poly->ST_CODE = $ExtendedData['ST_CODE'];
    $poly->ST_NAME = $ExtendedData['ST_NAME'];
    $poly->DIST_NAME= $ExtendedData['DIST_NAME'];
    $poly->AC_NAME= $ExtendedData['AC_NAME'];
    $poly->PC_NO= $ExtendedData['PC_NO'];
    $poly->PC_NAME= $ExtendedData['PC_NAME'];
    $poly->PC_ID= $ExtendedData['PC_ID'];
    $poly->Shape_Leng= $ExtendedData['Shape_Leng'];
    $poly->Shape_Area= $ExtendedData['Shape_Area'];
    $poly->MaxSimpTol= $ExtendedData['MaxSimpTol'];
    $poly->MinSimpTol= $ExtendedData['MinSimpTol'];
    $poly->acno = $ExtendedData['AC_NO'];
    
    $poly->poly = new CDbExpression("ST_PolygonFromText('POLYGON(" . implode(',',$polystring) . ")')");
    if(!$poly->save())
        print_r($poly->errors);
        echo $poly->acno . "\n";
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
    print_r($c3);
    return $c3;
}