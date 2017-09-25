<?php
$f = fopen('PreliminaryNotification-ocr2.txt','r');
$lc = 0;
while(!feof($f))
{
    $line = fgets($f);
    if(empty(trim($line)))
        continue;
    $lc++;
    $ward = [];
    $mats = [];
    if(!preg_match_all('/\((?<xy>X[^\)]+)\)/',$line,$mats))
    {
        die("Could not regex line:$lc: $line\n");
    }
    $xy_hold = [];
    foreach($mats['xy'] as $xy)
    {
        $mats2 = [];
        preg_match('/(?<x>[\d.]+)[^\d]*(?<y>[\d.]+)/', $xy, $mats2);
        //print_r($mats2);
        $xy_hold[] = [floatval($mats2['x']),floatval($mats2['y'])];
    }
    makegeofile($lc,$xy_hold);
    $ward[] = $xy_hold;
    echo "Ward $lc\n";
    print_r($ward);
}
fclose($f);

function makegeofile($lc,$xy)
{
    /*$coords = implode(',',array_reduce(xy, function($rt,$data){
        $rt[] = '[' . implode(',',$data) . ']';
        return $rt;
    }));*/
    /*$geo = fopen("geo-$lc.geojson",'w');
    fputs($geo,<<< GEOJSON
    {
      "type": "FeatureCollection",
      "features": []
    }
GEOJSON;
    fclose($geo);*/
    $json = [
                'type' => 'FeatureCollection',
                'features' =>
                [
                    [
                        'type' => 'Feature',
                        'properties' => [
                            'name' => "Ward $lc",
                        ],
                        'geometry' =>
                        [
                            'type' => 'Polygon',
                            'coordinates' => [$xy],
                        ]
                    ]
                ]
            ];
    print "\n" . json_encode($json,JSON_PRETTY_PRINT) . "\n\n";

    $geo = fopen("geo-$lc.geojson",'w');
    fputs($geo,json_encode($json,JSON_PRETTY_PRINT));
    fclose($geo);

    if($lc==2) die;
}
?>
