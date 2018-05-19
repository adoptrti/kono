<?php
function updateActionPuducherry()
{
    $id_election = 28;
    $id_state = 41; // puducherry
    $ST_CODE = 34; // puducherry
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            '' 
    ];
    
    $stateobj = State::model()->findByPk($id_state);
    $eleobj = Election::model()->findByPk($id_election);
    
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/puducherry/mlas.html' ) );
        
        // since its the only table
        $xpath = new DOMXpath ( $doc );
        $DIVs = $xpath->query ( "//div[@class='moduleHolder']" );
        
        if ($DIVs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        
        $rctr = 0;
        
        foreach ( $DIVs as $div )
        {
            #echo $doc->saveHTML($div);
            $h3s = $div->getElementsByTagName ( 'h3' );
            if ($h3s->length != 1)
                break;
            $h3 = $h3s->item ( 0 );
            $mats = [ ];
            
            $imgs = $div->getElementsByTagName ( 'img' );
            if ($imgs->length != 1)
                throw new Exception ( "No img found = " . $imgs->length  . " src=" . $imgs->item(0)->getAttribute('src'));
            $imgtag = $imgs->item ( 0 );
            $img = "https://www.py.gov.in/" . $imgtag->getAttribute('src');
            $img = str_replace("../", "", $img);
            
            if (! preg_match ( "/,(?<acname>[^,]+),\s*Puducherry/", $h3->nodeValue, $mats ))
                throw new Exception ( "H3 acname not found!? in [" . $h3->nodeValue . ']' );
            
            $acname = preg_replace ( "/\([^\)]+\)/", '', trim ( $mats ['acname'] ) );
            $acname = trim ( preg_replace ( "/\s+/", ' ', $acname ) );
            
            $acobj = $consti = Constituency::model ()->findByAttributes ( [ 
                    'id_state' => $id_state,
                    'ctype' => 'AMLY' 
            ], [ 
                    'condition' => 'name=:name1 or other_names=:name1',
                    'params' => [ 
                            'name1' => $acname 
                    ] 
            ] );
            if (! $consti)
                throw new Exception ( "acname not found as constituency in [" . $acname . ']' );
            $acno = $consti->eci_ref;
            
            $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
            $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
            $picture_path = $stateobj->slug . '/' . $outfile;
            if (! file_exists ( $p1 ))
                mkdir ( $p1 );
            $p2 = $p1 . '/' . $outfile;
            if (! file_exists ( $p2 ) || filesize ( $p2 ) == 0)
            {
                echo "Getting... " . $img . "\n";
                $img_data = @file_get_contents ( $img );
                if ($img_data)
                    file_put_contents ( $p2, $img_data );
                else
                    echo "Could not get file\n";
            } else
                echo "Found file $p2\n";
            
            $lis = $div->getElementsByTagName ( 'li' );
            if ($lis->length != 1)
                die ( 'LIs for name not found:' . $lis->length );
            
            $name = $lis->item ( 0 )->nodeValue;
            
            // ignore the first one
            // if ($rctr ++ == 0)
            // continue;
            
            $tds = $div->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = [ ];
            // $picture_path = null;
            for($i = 0; $i < $tds->length; $i ++)
            {
                $col = $i;
                $td = $tds->item ( $i );
                echo "$col = " . $td->nodeValue . "\n";
                switch (trim ( $td->nodeValue ))
                {
                    case 'Address' : // member name
                        $address = trim ( $tds->item ( $i + 2 )->nodeValue );
                        break;
                    case 'Mobile' : // ADDRESS
                        $phones [] = trim ( $tds->item ( $i + 2 )->nodeValue );
                        break;
                    case 'Residence' : // ADDRESS
                        $phones [] = trim ( $tds->item ( $i + 2 )->nodeValue );
                        break;
                    case 'Email' : // ADDRESS
                        $email = trim ( $tds->item ( $i + 2 )->nodeValue );
                        break;
                } // switch
            } // foreach TDs
            echo "eci:$acno\nName:$name\nAddress:$address\nEmail:$email\nPhone:" . implode ( ", ", $phones ) . "\n\n";
            continue;
            $MLA = AssemblyResults::model ()->findByAttributes ( [ 
                    'st_code' => $ST_CODE,
                    'id_election' => $id_election,
                    'acno' => $acobj->eci_ref 
            ] );
            if (! $MLA)
                $MLA = new AssemblyResults ();
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acobj->name;
            $MLA->acno = $acobj->eci_ref;
            $MLA->name = $name;
            $MLA->party = $party;
            // $MLA->phones = $phones;
            // $MLA->address = $address;
            // $MLA->emails = null;
            // $MLA->picture = $picture_path;
            $MLA->id_consti = $acobj->id_consti;
            $MLA->id_state = $acobj->id_state;
            $MLA->st_code = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acobj->eci_ref );
            }
            echo $MLA->acname . " saved!\n";
        } // foreach TRs
    } // foreach URLs
}
