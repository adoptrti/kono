<?php

function KeralaVillages()
{
    $id_state = 19;//kerala
    $ST_CODE = 32;
    
    Village::model()->deleteAllByAttributes(['id_state' => $id_state]);
    
    $stateobj = State::model ()->findByPk ( $id_state );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    
    $doc = new DOMDocument ();
    $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/kerala/villages.html' ) );
    
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
                echo 'Could not find district ' . $dist_name . "\n";
                $place = new Place();
                $place->id_state = $id_state;
                $place->sdt_code = $place->tv_code = $place->dt_code = 0;
                $place->dt_name = $dist_name;
                $place->name = $dist_name;
                $place->state_code = $ST_CODE;
                $place->updated = date('Y-m-d H:i:s');
                $place->eci_ref = null;
                $place->sdt_name = null;
                if(!$place->save())
                {
                    die('Count not save new District:' . print_r($place->errors,true));
                }
            }
            $id_district = $dists [$dist_name] = $place->id_place2;
        }
        
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
        }
        echo "\r $rctr\t" . $vill->village . "\t" . $vill->ward;
    } // foreach TRs
}
