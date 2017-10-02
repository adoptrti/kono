<?php
function updateActionGoa()
{
    $id_election = 27;
    $id_state = 12;
    $ST_CODE = 30;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://www.goavidhansabha.gov.in/member-of-assembly.php' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );
        
        // since its the only table
        $xpath = new DOMXpath ( $doc );
        $TRs = $xpath->query ( "//div[@class='mlamaindiv']" );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            $row = [ ];
            foreach ( $tr->childNodes as $ch )
            {
                if ($ch->nodeName == 'img')
                {
                    if (preg_match ( '/members/', $ch->getAttribute ( 'src' ) ))
                        $row [] = $ch->getAttribute ( 'src' );
                }
                else
                    $row [] = trim ( $ch->nodeValue );
            }
            
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            $acobj = null;
            
            foreach ( $row as $col => $td )
            {
                switch ($col)
                {
                    case 3 : // member nane
                        $name = trim ( $td );
                        break;
                    case 6 : // constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/(?<acno>\d+)?[\W]*(?<acname>\w[\s\w\.]+\w)/', $td, $mats ))
                                die ( "No match for [" . $td  . ']');
                            
                            $acname_fixes = [ 
                                    //'Panjim' => 'Panji',
                            ];
                            
                            
                            $find = array_keys ( $acname_fixes );
                            $replace = array_values ( $acname_fixes );
                            $acname = str_ireplace ( $find, $replace, $mats ['acname'] );
                            
                            $acno = intval ( $mats ['acno'] );
                            
                            if($acname == 'Panjim')
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
                                die ( '>> Could not find assembly [' . $acname . "]\n" . print_r($attr,true));
                            break;
                        }
                    case 9 : // PARTY
                        $party = trim ( str_replace ( ':', '', $td ) );
                        break;
                    case 2 : // picture
                        $img = $td;
                        break;
                } // switch
            } // foreach TDs
            
            $outfile = $stateobj->slug . '_AC_' . $acobj->slug . '_' . $eleobj->year . '.jpg';
            $p1 = realpath ( Yii::app ()->basePath . '/../images/pics' ) . '/' . $stateobj->slug;
            $picture_path = $stateobj->slug . '/' . $outfile;
            if (! file_exists ( $p1 ))
                mkdir ( $p1 );
            $p2 = $p1 . '/' . $outfile;
            echo "Getting... [" . $img . "]\n";
            $img_data = @file_get_contents ( $img );
            if ($img_data)
                file_put_contents ( $p2, $img_data );
            else
                echo "Could not get file\n";
            
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
