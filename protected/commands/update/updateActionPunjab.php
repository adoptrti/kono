<?php

function updateActionPunjab()
{
    $id_election = 35;
    $id_state = 29; // punjab
    $ST_CODE = 3; // punjab
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://punjab.gov.in/mlas' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/punjab/mlas.html' ) );
        
        // since its the only table
        $TRs = $doc->getElementsByTagName ( 'tr' );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            $tds = $tr->getElementsByTagName ( 'td' );
            $col = 0;
            $phones = null;
            // $picture_path = null;
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 0 :
                        $acno = intval ( $td->nodeValue );
                        break;
                    case 1:
                        $acname = $td->nodeValue;
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
                    case 2 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
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
            echo "$acno\t" . $MLA->acname . " saved!\n";
        } // foreach TRs
    } // foreach URLs
}
