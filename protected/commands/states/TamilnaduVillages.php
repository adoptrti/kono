<?php

function TamilnaduVillages()
{
    $id_state = 32; // tamilnadu
    $ST_CODE = 33; // tamilnadu
    
    $vills = Village::model ()->findAll ( 
            [ 
                    'select' => 'distinct dist_name',
                    'condition' => 'id_district is null' 
            ] );
    
    
    foreach ( $vills as $dist )
    {
        $dist_name = $dist->dist_name;
        
        if(empty(trim($dist_name)) || strlen($dist_name)==1)
            continue;
        
        echo "$dist_name...\n";   
        if ($dist_name == 'KANCHIPURAM')
            $dist_name = 'Kancheepuram';
        else if ($dist_name == 'NILGIRIS')
            $dist_name = 'The Nilgiris';
        else if ($dist_name == 'THOOTHUKUDI')
            $dist_name = 'Thoothukkudi';
        else if ($dist_name == 'TIRUVALLUR')
            $dist_name = 'Thiruvallur';
        else if('TIRUVARUR' ==  $dist_name)
            $dist_name = 'Thiruvarur';
        else if('VILLUPURAM' == $dist_name)
            $dist_name = 'Viluppuram';
        
        $place = Place::model ()->findByAttributes ( 
                [ 
                        'id_state' => $id_state,
                        'dt_name' => $dist_name,
                        'sdt_code' => 0,
                        'tv_code' => 0 
                ] );
        if (! $place)
        {
            die('Could not find district [' . $dist_name . "]\n");
        }
        Village::model()->updateAll(['id_district' => $place->id_place2],'dist_name = ?',[$dist->dist_name]);
    }
    
    return;
    
    // Village::model()->deleteAllByAttributes(['id_state' => $id_state]);
    
    $stateobj = State::model ()->findByPk ( $id_state );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    
    $outcsv = fopen ( Yii::app ()->basePath . '/../docs/tamilnadu/villages.csv', 'w' );
    
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
        $rctr ++;
        $col = 0;
        $allrows = [ ];
        $row = [ ];
        $ud = 0;
        $unknown_districts = [ ];
        // $picture_path = null;
        foreach ( $tds as $td )
        {
            // echo "COL: $col = " . $td->nodeValue . "\n";
            $row [$col ++] = trim ( $td->nodeValue );
            $allrows [] = $row;
        } // foreach TDs
        
        $dist_name = $row [2];
        
        if (empty ( $dists [$dist_name] ))
        {
            if ($dist_name == 'KANCHIPURAM')
                $dist_name = 'Kancheepuram';
            else if ($dist_name == 'NILGIRIS')
                $dist_name = 'The Nilgiris';
            else if ($dist_name == 'THOOTHUKUDI')
                $dist_name = 'Thoothukkudi';
            else if ($dist_name == 'TIRUVALLUR')
                $dist_name = 'Thiruvallur';
        }
        
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
                $unknown_districts [$dist_name] = -- $ud;
            }
            $id_district = $dists [$dist_name] = $place->id_place2;
        }
        
        fputcsv ( $outcsv, [ 
                $id_state,
                $dists [$dist_name],
                $row [3],
                $row [4],
                $row [5],
                $row [6] 
        ] );
        /*
         * $vill = new Village ();
         * $vill->id_state = $id_state;
         * $vill->id_district = $dists [$dist_name];
         * $vill->block = $row [3];
         * $vill->panchayat = $row [4];
         * $vill->village = $row [5];
         * $vill->ward = $row [6];
         *
         * if (! $vill->save ())
         * {
         * print_r ( $vill->errors );
         * die ( 'Saving Village failed for ' . $vill->village . ' - ' .
         * $vill->ward );
         * }
         */
        if ($rctr % 1000 == 0)
            echo sprintf ( "\r%06d\t%02d Districts\t%30s", $rctr, count ( $dists ), $dist_name );
    } // foreach TRs
    
    fclose ( $outcsv );
    
    print_r ( $unknown_districts );
}


/*
    LOAD DATA  
        LOCAL INFILE '/Volumes/Untitled/projects/konoweb/docs/tamilnadu/villages.csv' INTO TABLE `villages2011` 
    CHARACTER SET latin1 
        FIELDS TERMINATED BY ',' 
        OPTIONALLY ENCLOSED BY '"' 
        ESCAPED BY '"' 
        LINES TERMINATED BY '\r\n' 
        (`id_state`, `id_district`, `block`, `panchayat`, `village`, `ward`);
        
        LOAD DATA LOCAL INFILE '/Volumes/Untitled/projects/konoweb/docs/tamilnadu/villages.csv' INTO TABLE `villages2011` 
        FIELDS TERMINATED BY ',' 
        OPTIONALLY ENCLOSED BY '"' 
        ESCAPED BY '"' 
        LINES TERMINATED BY '\r\n' 
        (`del1`, `del2`, `state_name`, `dist_name`, `block`, `panchayat`, `village`, `ward`);
/
        
        mysqlimport --fields-terminated-by=, --columns='id_state,id_district,block,panchayat,village,ward' --local -u root -p eci3 villages2011.csv 
 */