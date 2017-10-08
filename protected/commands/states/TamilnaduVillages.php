<?php

function TamilnaduVillages()
{
    $id_state = 32; // tamilnadu
    $ST_CODE = 33; // tamilnadu
    
    //Village::model()->deleteAllByAttributes(['id_state' => $id_state]);
    
    $stateobj = State::model ()->findByPk ( $id_state );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    
    $outcsv = fopen(Yii::app ()->basePath . '/../docs/tamilnadu/villages.csv','w');
    
    $doc = new DOMDocument ();
    $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/tamilnadu/villages.html' ) );
    
    $TABLE = $doc->getElementById ( 'ctl00_ContentPlaceHolder_GVHabitation' );
    
    $TRs = $TABLE->getElementsByTagName ( 'tr' );
    
    $dists = [ ];
    
    foreach ( $TRs as $tr )
    {
        // if (! $rctr ++)
        // continue;
        
        $tds = $tr->getElementsByTagName ( 'td' );
        if ($tds->length < 3)
            continue;
        $rctr++;
        $col = 0;
        $row = [ ];
        // $picture_path = null;
        foreach ( $tds as $td )
        {
            // echo "COL: $col = " . $td->nodeValue . "\n";
            $row [$col ++] = trim ( $td->nodeValue );
        } // foreach TDs
        
        $dist_name = $row [2];
        
        if ($dist_name == 'KANCHIPURAM')
            $dist_name = 'Kancheepuram';
        else if ($dist_name == 'NILGIRIS')
            $dist_name = 'The Nilgiris';
        else if($dist_name = 'THOOTHUKUDI')
            $dist_name = 'Thoothukkudi';
        
        if (empty ( $dists [$dist_name] ))
        {
            
            $place = Place::model ()->findByAttributes ( 
                    [ 
                            'id_state' => $id_state,
                            'dt_name' => $dist_name,
                            'sdt_code' => 0,
                            'tv_code' => 0 
                    ] );
            if (! $place)
            {
                die ( 'Could not find district ' . $dist_name);
            }
            $id_district = $dists [$dist_name] = $place->id_place2;
        }
        
        fputcsv($outcsv,[$id_state,$dists [$dist_name],$row [3],$row [4],$row [5],$row [6]]);
        /*
        $vill = new Village ();
        $vill->id_state = $id_state;
        $vill->id_district = $dists [$dist_name];
        $vill->block = $row [3];
        $vill->panchayat = $row [4];
        $vill->village = $row [5];
        $vill->ward = $row [6];
        
        if (! $vill->save ())
        {
            print_r ( $vill->errors );
            die ( 'Saving Village failed for ' . $vill->village . ' - ' . $vill->ward );
        }*/
        if($rctr % 100 == 0)
            echo sprintf("\r%06d\t%30s",$rctr,$dist_name);
    } // foreach TRs
    
    fclose($outcsv);
}
