<?php

function updateActionPuducherry()
{
    $id_election = 28;
    $id_state = 41;
    $ST_CODE = 34;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://odishaassembly.nic.in/profile_detail.aspx?x=6' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/orissa/mlas.html' ) );
        
        // since its the only table
        $table = $doc->getElementById ( 'ctl00_ContentPlaceHolder1_GridView1' );
        
        $TRs = $table->getElementsByTagName ( 'tr' );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            // ignore the first one
            if ($rctr ++ == 0)
                continue;
            
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            // $picture_path = null;
            foreach ( $tds as $td )
            {
                echo "$col = " . $td->nodeValue . "\n";
                switch ($col ++)
                {
                    case 1 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    case 2 : // constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/(?<acno>\d+)?[\W]*(?<acname>\w[\s\w\.]+\w)/', $td->nodeValue, $mats ))
                                die ( "No match for [" . $td . ']' );
                            
                            $acname_fixes = [                                // 'Panjim' => 'Panji',
                            ];
                            
                            $find = array_keys ( $acname_fixes );
                            $replace = array_values ( $acname_fixes );
                            $acname = str_ireplace ( $find, $replace, $mats ['acname'] );
                            
                            $acno = intval ( $mats ['acno'] );
                            
                            if ($acname == 'Panjim')
                            {
                                $acno = 11;
                            }
                            
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    $attr = [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            if (! $acobj)
                                die ( '>> Could not find assembly [' . $acname . "]\n" . print_r ( $attr, true ) );
                            break;
                        }
                    case 3 : // PARTY
                        $party = trim ( $td->nodeValue );
                        break;
                } // switch
            } // foreach TDs
            
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
