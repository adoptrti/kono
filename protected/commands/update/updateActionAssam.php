<?php

function updateActionAssam()
{
    $id_election = 34;
    $id_state = 9; // assam
    $ST_CODE = 18; // puducherry
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    $urls = [ 
            'http://meghalaya.gov.in/megportal/government/mlas',
            'http://meghalaya.gov.in/megportal/government/mlas?page=1' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/meghalaya/mlas1.html' ) );
        $div = $doc->getElementById('mla_custom');        
        // since its the only table
        $TRs = $div->getElementsByTagName ( 'tr' );
        
        foreach ( $TRs as $tr )
        {
            if (! $rctr ++)
                continue;
            
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            // $picture_path = null;
            foreach ( $tds as $td )
            {        
                switch ($col ++)
                {
                    case 0 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        $img = '';
                        if ($imgs->length > 0)
                            $img = 'http://www.assamassembly.gov.in/' . $imgs->item ( 0 )->getAttribute ( 'src' );
                        
                        break;
                    case 1 : // constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/(?<acno>\d+)-(?<acname>[\s\(\)\w]+)/', $td->nodeValue, $mats ))
                                die ( "No match for [" . $td . ']' );
                            
                            $acno = intval ( $mats ['acno'] );
                            $acname =  $mats ['acname'] ;
                            
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    $attr = [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            if (! $acobj)
                            {
                                echo  '>> Making not found assembly [' . $td->nodeValue. "]\n" . print_r ( $attr, true );
                                $acobj = new Constituency();
                                $acobj->id_state = $id_state;
                                $acobj->ctype = 'AMLY';
                                $acobj->eci_ref = $acno;
                                $acobj->name = $acname;
                                if(!$acobj->save())
                                    die("Could not save new contituency:" . print_r($acobj->errors,true));
                            }
                            break;
                        }
                    case 2 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    
                    case 3 : // PARTY
                        $party = trim ( $td->nodeValue );
                        break;
                } // switch
            } // foreach TDs
            
            $picture_path = null;
            if (! empty ( $img ))
            {
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
            
            $MLA = AssemblyResults::model ()->findByAttributes ( 
                    [ 
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
            $MLA->picture = $picture_path;
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
