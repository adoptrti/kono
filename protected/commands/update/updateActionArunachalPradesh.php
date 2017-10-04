<?php

function updateActionArunachalPradesh()
{
    $id_election = 30; //arunachal
    $id_state = 8;//arunachal
    $ST_CODE = 12;//arunachal
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://arunachalpradesh.gov.in/?page_id=830' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/arunachalpradesh/mlas.html' ) );
        
        $td = $doc->getElementById('tablepress-65');
        // since its the only table
        $TRs = $td->getElementsByTagName ( 'tr' );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        $rctr = 0;
        
        foreach ( $TRs as $tr )
        {
            // ignore the first one
            if ($rctr ++ < 1)
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
                    case 1 : // acno, constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/(?<acno>\d+)/', $td->nodeValue, $mats ))
                            {
                                echo 'Not parsed constituency:[' . $td->nodeValue . "]\n";
                                $col = 10;
                                break;
                            }
                            $acno = $mats['acno'];
                            
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            if (! $acobj)
                                die ( '>> Could not find assembly ' . $acno. " - " . $td->nodeValue . "\n" );
                            break;
                        }
                    case 2 : // mobile+emails
                        $name = $td->nodeValue;
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
            $MLA->emails = $MLA->address = $MLA->phones = $MLA->party = null;
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
