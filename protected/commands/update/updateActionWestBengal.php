<?php

function updateActionWestBengal()
{
    $id_election = 29;
    $id_state = 36;
    $ST_CODE = 19;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://wbassembly.gov.in/MLA_All.aspx' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/westbengal/mlas.html' ) );
        
        // since its the only table
        $table = $doc->getElementById ( 'MainContent_gvMember' );
        
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
                    case 0 : // sno
                             // ignore
                        break;
                    case 1 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        if ($imgs->length < 1)
                            die ( 'Not found img in ' . $acno );
                        $img = 'http://wbassembly.gov.in/' .
                                 str_replace ( '../', '', $imgs->item ( 0 )->getAttribute ( 'src' ) );
                    case 2 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    case 3 : // acno
                        $acno = trim ( $td->nodeValue );
                        
                        $acobj = Constituency::model ()->findByAttributes ( 
                                $attr = [ 
                                        'id_state' => $id_state,
                                        'ctype' => 'AMLY',
                                        'eci_ref' => $acno 
                                ] );
                        if (! $acobj)
                            die ( '>> Could not find assembly [' . $acname . "]\n" . print_r ( $attr, true ) );
                        break;
                    case 5 : // PARTY
                        $party = trim ( $td->nodeValue );
                        break;
                } // switch
            } // foreach TDs
            
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
