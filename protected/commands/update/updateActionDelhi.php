<?php

function updateActionDelhi()
{
    $id_election = 21;
    $ST_CODE = 7;
    $id_state = 1;
    
    $doc = new DOMDocument ();
    $doc->loadHTML ( file_get_contents ( 'http://delhiassembly.nic.in/aspfile/listmembers_VIth_Assembly.htm' ) );
    
    $TRs = $doc->getElementsByTagName ( 'tr' );
    if ($TRs->length == 0)
        die ( 'Delhi Assembly parsing failed.' );
    $rctr = 0;
    foreach ( $TRs as $tr )
    {
        // ignore the first one
        if ($rctr ++ == 0)
            continue;
        
        $tds = $tr->getElementsByTagName ( 'td' );
        $col = 0;
        foreach ( $tds as $td )
        {
            switch ($col ++)
            {
                case 1 :
                    $name = trim ( $td->nodeValue );
                    break;
                case 2 :
                    $party = trim ( $td->nodeValue );
                    break;
                case 3 :
                    $address = trim ( str_replace ( '<br />', '', $td->nodeValue ) );
                    break;
                case 4 :
                    $phones = trim ( $td->nodeValue );
                    break;
                case 5 :
                    {
                        $mats1 = [ ];
                        if (preg_match ( '/^(?<acname>[^\(]+)\((?<acno>\d+)\)/', $td->nodeValue, $mats1 ))
                        {
                            $acname = $mats1 ['acname'];
                            $acno = $mats1 ['acno'];
                        }
                        else
                        {
                            error_log ( __FILE__ . ':' . __LINE__ . ': Error: ' . $td->nodeValue );
                            die ( 'Failed parsing ACNO/ACNAME' );
                        }
                        break;
                    }
                case 6 :
                    $emails = trim ( $td->nodeValue );
                    break;
            } // switch($coll)
        } // foreach
        
        $MLA = AssemblyResults::model ()->findByAttributes ( 
                [ 
                        'ST_CODE' => $ST_CODE,
                        'id_election' => $id_election,
                        'acno' => $acno 
                ] );
        if (! $MLA)
            $MLA = new AssemblyResults ();
        
        $attr = [ 
                'eci_ref' => $acno,
                'ctype' => 'AMLY',
                'id_state' => $id_state 
        ];
        
        $consti = Constituency::model ()->findByAttributes ( $attr );
        if (! $consti)
        {
            error_log ( __FILE__ . ':' . __LINE__ . ': Error: ' . print_r ( $attr, true ) );
            die ( 'count not find consti\n' );
        }
        
        $MLA->id_election = $id_election;
        $MLA->acname = $acname;
        $MLA->acno = $acno;
        $MLA->name = $name;
        $MLA->party = $party;
        $MLA->phones = $phones;
        $MLA->address = $address;
        $MLA->emails = $emails;
        $MLA->id_consti = $consti->id_consti;
        $MLA->id_state = $consti->id_state;
        $MLA->ST_CODE = $ST_CODE;
        
        if (! $MLA->save ())
        {
            print_r ( $MLA->errors );
            die ( 'Saving MLA failed for ' . $acno );
        }
    } // foreach($TR)
}