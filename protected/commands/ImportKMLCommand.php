<?php
class ImportKMLCommand extends CConsoleCommand
{

    public function actionIndex()
    {
        $dom = new DOMDocument ();
        $file = realpath ( '../../docs/tn-maps/TAMILNADU-AC.kml' );
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
    echo __LINE__ . "\n";
    $ExtendedData = parseExtendedData ( $place );
    $coords = parseCoords ( $place );
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

function insertRow($ExtendedData, $coords)
{
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

function parseCoords($node)
{
    $eles = $node->getElementsByTagName ( 'coordinates' );
    if ($eles->length == 0)
        die ( "Not found coords\n" );
    $ele = $eles->item ( 0 );
    $c2 = array_map ( 
            function ($item)
            {
                $a = explode ( ',', $item );
                if (count ( $a ) == 3)
                    return $a;
            }, explode ( ' ', $ele->nodeValue ) );
    
    return $c2;
}