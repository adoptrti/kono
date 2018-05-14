<?php
// 10.856975354531784,77.1036683713969
// select acno FROM acpoly where ST_Contains(acpoly, GeomFromText('POINT(10.856975354531784 77.1036683713969)'));
class ImportGJSONCommand extends CConsoleCommand
{
    var $wardids;
    public function actionIndex($file)
    {
        for($i = 1; $i <= 150; $i ++)
            $this->wardids [$i] = 0;
        
        /*
         * print_r($_GET);
         * print_r($_SERVER);
         * die;
         */
        $file = realpath ( $file );
        
        echo "Loaded file $file\n";
        $data = json_decode ( file_get_contents ( $file ) );
        
        $pctr = 1;
        
        foreach ( $data->features as $place )
        {
            $this->parseplace ( $place, $pctr ++ );
        }
        
        $rs = AssemblyPolygon::model ()->findAll ( [ 
                'group' => 'ST_NAME',
                'select' => 'ST_NAME,count(*) as ctr1' 
        ] );
        
        foreach ( $rs as $r )
        {
            echo "{$r->st_name}\t{$r->ctr1}\n";
        }
        if (count ( $this->wardids ))
        {
            echo "These ward numbers were not found.";
            print_r ( $this->wardids );
        }
    }
    function parseplace($place, $pctr)
    {
        $ExtendedData = $this->parseExtendedData ( $place );
        $coords = $this->parseManyCoords ( $place );
        $this->insertRow ( $ExtendedData, $coords, $pctr );
    }
    function extractCoords($many_coords, &$polystring)
    {
        foreach ( $many_coords as $coords )
        {
            // $coords is an array or array, which is reduced into
            // 1 array of strings imploded into 1 comma seperated string.
            $polystring [] = '(' . implode ( ',', array_reduce ( $coords, function ($carry, $item)
            {
                if (empty ( $item [0] ))
                    return $carry;
                
                $carry [] = $item [0] . ' ' . $item [1];
                return $carry;
            } ) ) . ')';
        }
    }
    
    // modified now for importing bangalore wards
    // 13-may-17 - modified to import hyderabad wards
    function insertRow($ExtendedData, $many_coords, $pctr)
    {
        $poly = new AssemblyPolygon ();
        
        $polystring = [ ];
        print_r ( $many_coords );
        if (count ( $many_coords ) == 1)
            $this->extractCoords ( $many_coords, $polystring );
        else
        {
            foreach ( $many_coords as $m1 )
                $this->extractCoords ( $m1, $polystring );
        }
        print_r ( $ExtendedData );
        
        $mats = [ ];
        
        if (! preg_match ( '/Ward (?<acno>\d+)/', $ExtendedData->name, $mats ))
            die ( "Could not match ward no  with [" . $ExtendedData->name . "]" );
        $poly->acno = $mats ['acno'];
        $this->setprops ( $poly, $ExtendedData, 'name', 'name' );
        
        // setprops($poly, $ExtendedData,'zone', 'Zone_No');
        // setprops($poly, $ExtendedData,'Shape_Area', 'AREA_SQ_KM');
        // setprops($poly, $ExtendedData,'Shape_Leng', 'PERIMETER');
        
        $poly->poly = new CDbExpression ( "ST_PolygonFromText('POLYGON(" . implode ( ',', $polystring ) . ")')" );
        
        // very important
        $poly->polytype = 'WARD';
        $poly->dist_name = 'Hyderabad';
        $poly->dt_code = 8360;
        $poly->st_code = 36; // diff from id_state
        $poly->id_state = 46;
        
        if (AssemblyPolygon::model ()->countByAttributes ( [ 
                'polytype' => $poly->polytype,
                'dt_code' => $poly->dt_code,
                'id_state' => $poly->id_state,
                'acno' => $poly->acno 
        ] ))
        {
            echo "Ward {$poly->acno} already exists.\n";
            unset ( $this->wardids [$poly->acno] );
            return false;
        }
        
        // $poly->ST_NAME = '';
        
        if (empty ( $poly->acno ))
        {
            echo "Invalid Data found. Ignoring\n";
            return;
        }
        
        if (! $poly->save ())
        {
            print_r ( $poly->errors );
            die ( 'died saving ward:' . $poly->wardno );
        }
        addWard ( $poly->dt_code, $poly->acno, $poly->dt_code, $poly->st_code );
        unset ( $this->wardids [$poly->acno] );
        
        echo $poly->acno . " (# $pctr) \n\n";
    }
    function addWard($id_city, $wardno, $dt_code, $st_code)
    {
        $MR = MunicipalResults::model ()->findByAttributes ( [ 
                'id_city' => $id_city,
                'wardno' => $wardno 
        ] );
        if (! $MR)
        {
            $MR = new MunicipalResults ();
            $MR->wardno = $wardno;
            // $MR->city = $city;
            $MR->id_city = $id_city; // bangalore towns2011
            $MR->dt_code = $dt_code;
            $MR->st_code = $st_code;
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
}