<?php
$dom = new DOMDocument ();
$file = realpath ( 'wards.html' );
if ($dom->loadHTMLFile( $file ) == false)
    die ( "could not load file :$file" );
echo "Loaded file $file\n";

$ele = $dom->getElementsByTagName ( 'div' );
echo $ele->length;

foreach ( $ele as $box )
{
    if($box->getAttribute('class') == 'councillor-bg' || $box->getAttribute('class') == 'box1')
    {
        $row = parsebox( $box );
        fputcsv(STDOUT, $row);
    }
}

function parsebox($box)
{
    $row = [];
    $ps = $box->getElementsByTagName('p');
    foreach($ps as $p)
    {
        $row[] = $p->nodeValue;        
    }
    return $row;
}
