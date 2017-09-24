<?php

function updateActionHimachal()
{
    $id_election = 25; // himachal
    $ST_CODE = 2; // himachal
    $id_state = 15; // himachal
    
    $stateobj = States::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://hpvidhansabha.nic.in/Member/AllMembers',
            'http://hpvidhansabha.nic.in/Member/AllMembers?page=2' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );
        
        $xpath = new DOMXpath ( $doc );
        $TABLEs = $xpath->query ( "//table[@class='tablestyle']" );
        
        if ($TABLEs->length == 0)
            die ( 'Assembly parsing failed. table not found' );
        
        $TRs = $TABLEs->item ( 0 )->getElementsByTagName ( 'tr' );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            // ignore the first one
            if ($rctr ++ == 0)
                continue;
            $acdone = [ ];
            $phone = [ ];
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 0 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        // delayed as it was the first col and we did not
                        // know enough to make a file name
                        break;
                    case 1 : // member nane
                        $name = cleanspace ( $td->nodeValue );
                        break;
                    case 2 : // constituency
                        {
                            $mats = [ ];
                            $acname = cleanspace ( $td->textContent );
                            
                            if (! preg_match ( '/^(?<acname>[^\d]+)(?<acno>\d+)$/', $acname, $mats ))
                                die ( 'Could not preg assembly ' . $acname );
                            $acname = $mats ['acname'];
                            $acno = $mats ['acno'];
                            echo "Got $acno - $acname >>\n";
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            if (! $acobj)
                                die ( 'Could not find assembly ' . $acname );
                            $acno = $acobj->eci_ref;
                            if (isset ( $acdone [$acno] ))
                                die ( 'Duplicate ' . $acname );
                            $acdone [$acno] = $acdone;
                            echo "Found $acname as " . $acobj->name . "\t" . $acno . "\n";
                            break;
                        }
                    case 3 : // address
                        $address = cleanspace ( $td->nodeValue );
                        break;
                    case 5 : // mobile
                    case 6 : // phone
                        $p1 = trim ( $td->nodeValue );
                        if (! empty ( $p1 ))
                            $phone [] .= cleanspace ( $p1 );
                        break;
                    case 7 : // email
                        $email = cleanspace ( $td->nodeValue, false );
                        break;
                } // switch
            } // foreach TDs
            
            if (! empty ( $imgs ))
            {
                if ($imgs->length < 1)
                    die ( 'Not found img in ' . $acno );
                $img = $imgs->item ( 0 )->getAttribute ( 'src' );
                $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
                $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
                $picture_path = $stateobj->slug . '/' . $outfile;
                if (! file_exists ( $p1 ))
                    mkdir ( $p1 );
                $p2 = $p1 . '/' . $outfile;
                echo "Getting... " . $img . "\n";
                $img_data = @file_get_contents ( $img );
                if ($img_data)
                    file_put_contents ( $p2, $img_data );
                else
                    echo "Could not get file\n";
            }
            
            $MLA = TamilNaduResults2016::model ()->findByAttributes ( 
                    [ 
                            'ST_CODE' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => $acno 
                    ] );
            if (! $MLA)
                $MLA = new TamilNaduResults2016 ();
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acname;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = null;
            $MLA->phones = implode ( ',', array_reduce ( $phone, 'reducer' ) );
            $MLA->address = $address;
            $MLA->emails = $email;
            $MLA->picture = $picture_path;
            $MLA->id_consti = $acobj->id_consti;
            $MLA->id_state = $acobj->id_state;
            $MLA->ST_CODE = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acno );
            }
        } // foreach TRs
    } // foreach URLs
}