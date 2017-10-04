<?php

function updateActionRajasthan()
{
    $id_election = 31;
    $id_state = 2;
    $ST_CODE = 8;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://www.rajassembly.nic.in/MemContacts.asp' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/rajasthan/mlas.html' ) );
        
        // since its the only table
        $TRs = $doc->getElementsByTagName ( 'tr' );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            // ignore the first one
            if ($rctr ++ < 3)
                continue;
            echo "$rctr\n";
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            // $picture_path = null;
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 1 : // name, constituency, party
                        {
                            $ss = explode ( "\n", $td->nodeValue );
                            print_r ( $ss );
                            $name = trim ( $ss [2] );
                            $party = trim ( $ss [3] );
                            $acname1 = trim ( $ss [count ( $ss ) - 1] );
                            
                            $mats = [ ];
                            if (! preg_match ( '/^(?<acname>[^\(]+)/', $acname1, $mats ))
                            {
                                echo 'Not parsed constituency:[' . $td->nodeValue . "]\n";
                                $col = 10;
                                break;
                            }
                            $acname = trim ( $mats ['acname'] );
                            if ($acname == 'Marwar Junciton')
                                $acname = 'Marwar Junction';
                            else if ($acname == 'Lachhmangarh')
                                $acname = 'Lachmangarh';
                            else if ($acname == 'Vallabnagar')
                                $acname = 'Vallabhnagar';
                            else if ($acname == 'Deoli-Uniara')
                                $acname = 'Deoli Uniara';
                            else if ($acname == 'Baran-Atru')
                                $acname = 'Baran Atru';
                            else if ($acname == 'Gudhamalani')
                                $acname = 'Gudha Malani';
                            else if ($acname == 'Deeg-Kumher')
                                $acname = 'Deeg Kumher';
                            else if ($acname == 'Pindwara-Abu')
                                $acname = 'Pindwara Abu';
                            else if ($acname == 'Khinvsar')
                                $acname = 'Khinwsar';
                            
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'name' => $acname 
                                    ] );
                            if (! $acobj)
                                die ( '>> Could not find assembly ' . $acname . "\n" );
                            break;
                        }
                    case 2 : // mobile+emails
                        $ss = explode ( "\n", trim ( $td->nodeValue ) );
                        $phone = $ss [0];
                        $email = null;
                        if (isset ( $ss [1] ))
                        {
                            $email = $ss [1];
                            $email = str_replace ( 
                                    [ 
                                            '[dot]',
                                            '[at]' 
                                    ], 
                                    [ 
                                            '.',
                                            '@' 
                                    ], trim ( $email ) );
                        }
                        break;
                    case 3 : // p address+phone
                        if (empty ( trim ( $td->nodeValue ) ))
                            continue;
                        $raw1 = explode ( "\n", $td->nodeValue );
                        print_r ( $raw1 );
                        $raw2 = [ ];
                        foreach ( $raw1 as $i )
                            // if(!empty(trim($i)) && strlen(trim($i))>0)
                            if (preg_match ( '/\w/', $i ))
                            {
                                // echo "[" . trim($i) . "] = " .
                                // strlen(trim($i)) . "\n";
                                $raw2 [] = trim ( $i );
                            }
                        $mats = [ ];
                        // check if last index looks like a phone
                        if (preg_match ( '/(?<phones>\d{3}[\d-]+)/', $raw2 [count ( $raw2 ) - 1], $mats ))
                        {
                            $phones = $mats ['phones'];
                            // echo "$acno\t$phones\n";
                            unset ( $raw2 [count ( $raw2 ) - 1] );
                            $address = implode ( ",", $raw2 );
                        }
                        else
                        {
                            $phones = null;
                            // print_r($raw2);
                            // echo ">>> No phone\n";
                            $address = implode ( ',', $raw2 );
                        }
                        break;
                } // switch
            } // foreach TDs
            
            $MLA = AssemblyResults::model ()->findByAttributes ( 
                    [ 
                            'st_code' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => intval ( $acobj->eci_ref ) 
                    ] );
            if (! $MLA)
                $MLA = new AssemblyResults ();
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acobj->name;
            $MLA->acno = $acobj->eci_ref;
            $MLA->name = $name;
            $MLA->party = $party;
            $MLA->phones = $phone;
            $MLA->address = $address;
            $MLA->emails = $email;
            // $MLA->picture = $picture_path;
            $MLA->id_consti = $acobj->id_consti;
            $MLA->id_state = $acobj->id_state;
            $MLA->st_code = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acobj->eci_ref . ' - ' . $acname );
            }
        } // foreach TRs
    } // foreach URLs
}
