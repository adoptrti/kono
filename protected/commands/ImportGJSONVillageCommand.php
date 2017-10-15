<?php
// 10.856975354531784,77.1036683713969
// select acno FROM acpoly where ST_Contains(acpoly,
// GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ImportGJSONVillageCommand extends CConsoleCommand
{

    public function actionIndex($file)
    {
        /*
         * print_r($_GET);
         * print_r($_SERVER);
         * die;
         */
        $file = realpath ( $file );
        /*AssemblyPolygon::model ()->deleteAllByAttributes ( [ 
                'polytype' => 'VILLAGE' 
        ] );*/
        echo "Loaded file $file\n";
        $data = json_decode ( file_get_contents ( $file ) );
        
        $pctr = 1;
        
        foreach ( $data->features as $place )
        {
            parseplace ( $place, $pctr ++ );
        }
        
        $rs = AssemblyPolygon::model ()->with ( [ 
                'state' 
        ] )->findAll ( 
                [ 
                        'group' => 't.id_state,state.name',
                        'select' => 'state.name,count(*) as ctr1' 
                ] );
        
        foreach ( $rs as $r )
        {
            echo "{$r->name}\t{$r->ctr1}\n";
        }
    }
}

function parseplace($place, $pctr)
{
    $ExtendedData = parseExtendedData ( $place );
    $coords = parseManyCoords ( $place );
    insertRow ( $ExtendedData, $coords, $pctr );
}

function insertRow($ExtendedData, $many_coords, $pctr)
{
    $poly = new AssemblyPolygon ( 'village' );
    
    $polystring = [ ];
    foreach ( $many_coords as $coords )
    {
        $polystring [] = '(' . implode ( ',', 
                array_reduce ( $coords, 
                        function ($carry, $item)
                        {
                            if (empty ( $item [0] ))
                                return $carry;
                            
                            $carry [] = $item [0] . ' ' . $item [1];
                            return $carry;
                        } ) ) . ')';
    }
    //print_r ( $ExtendedData );
    
    // match with state id
    $state_code = trim ( $ExtendedData->stat_name );
    $state = State::model ()->cache(3600)->findByAttributes ( [ 
            'ias_short_code' => $state_code 
    ] );
    if (! $state)
        die ( 'Not found any state with code:' . $state_code );
    
    if (! isset ( $ExtendedData->dist_name ))
    {
        #echo 'District not known for village:' . $ExtendedData->name . "\n";
    }
    // match with district id in state
    if (! empty ( $ExtendedData->dist_name ))
    {
        $dist_name = trim ( $ExtendedData->dist_name );
        $district = District::model ()->cache(3600)->findByAttributes ( 
                [ 
                        'id_state' => $state->id_state,
                        'name' => $dist_name 
                ] );
        if (! $district)
        {
            #echo 'Not found any district with name:' . $dist_name . "\n";
        }
        
    }
    if (isset ( $district->id_district ))
    {
        $poly->id_district = $district->id_district;
        // find village
        $village = LBVillage::model ()->with ( [ 
                'panchayat',
                'panchayat.block' 
        ] )->cache(3600)->findByAttributes ( [ 
                'name' => trim ( $ExtendedData->name ) 
        ], 
                [ 
                        'condition' => 'block.id_district = ?',
                        'params' => [ 
                                $district->id_district 
                        ] 
                ] );
        
        if (! $village)
        {
            #echo 'Not found any village with name:' . $ExtendedData->name . "\n";
        }
    }
    
    setprops ( $poly, $ExtendedData, 'Shape_Area', 'shape_area' );
    setprops ( $poly, $ExtendedData, 'Shape_Leng', 'shape_len' );
    setprops ( $poly, $ExtendedData, 'dstcd', 'dt_code' );
    setprops ( $poly, $ExtendedData, 'sdstcd', 'sdt_code' );
    setprops ( $poly, $ExtendedData, 'sdst_name', 'sdt_name' );
    setprops ( $poly, $ExtendedData, 'dist_name', 'dist_name' );
    setprops ( $poly, $ExtendedData, 'stat_name', 'st_name' );
    setprops ( $poly, $ExtendedData, 'name', 'name' );
    
    $poly->poly = new CDbExpression ( "ST_PolygonFromText('POLYGON(" . implode ( ',', $polystring ) . ")')" );
    // very important
    $poly->polytype = 'VILLAGE';
    $poly->id_state = $state->id_state;
    if (isset ( $village->id_village ))
        $poly->id_village = $village->id_village;
        
    if (! $poly->save ())
    {
        print_r ( $poly->errors );
        die ( 'died saving ward:' . $poly->wardno );
    }
    
    #echo $poly->name . " (# $pctr) \r";
}

function addWard($city, $wardno, $dt_code, $st_code)
{
    $MR = MunicipalResults::model ()->findByAttributes ( 
            [ 
                    'city' => $city,
                    'wardno' => $wardno 
            ] );
    if (! $MR)
    {
        $MR = new MunicipalResults ();
        $MR->wardno = $wardno;
        $MR->city = $city;
        $MR->DT_CODE = $dt_code;
        $MR->ST_CODE = $st_code;
        if (! $MR->save ())
        {
            print_r ( $MR->errors );
            die ( 'died saving ' . $wardno );
        }
    }
}

function setprops($poly, $ExtendedData, $setter, $field)
{
    if (! empty ( $ExtendedData->$field ))
        $poly->$setter = $ExtendedData->$field;
}

function parseExtendedData($node)
{
    return $node->properties;
}

/*
 * function parseCoords($ele)
 * {
 * $c2 = array_map (
 * function ($item)
 * {
 * $a = explode ( ',', $item );
 * if (count ( $a ) == 3)
 * return $a;
 * }, explode ( ' ', trim($ele->nodeValue) ) );
 * return $c2;
 * }
 */
function parseManyCoords($node)
{
    foreach ( $node->geometry->coordinates as $ele )
        $c3 [] = $ele;
    
    return $c3;
}
