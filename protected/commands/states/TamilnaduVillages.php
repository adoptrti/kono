<?php

function TamilnaduVillages()
{
    $id_state = 32; // tamilnadu
    $ST_CODE = 33; // tamilnadu
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    $rctr = 0;
    
    $doc = new DOMDocument ();
    $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/tamilnadu/villages.html' ) );
    
    $TABLE = $doc->getElementById('ctl00_ContentPlaceHolder_GVVillage');
    
    $TRs = $TABLE->getElementsByTagName ( 'tr' );
    
    foreach ( $TRs as $tr )
    {
        if (! $rctr ++)
            continue;
        
        $tds = $tr->getElementsByTagName ( 'td' );
        $col = 0;
        
        // $picture_path = null;
        foreach ( $tds as $td )
        {
            // echo "COL: $col = " . $td->nodeValue . "\n";
            $row[$col++] = trim($td->nodeValue);            
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
}
