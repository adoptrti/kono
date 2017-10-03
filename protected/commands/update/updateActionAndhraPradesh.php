<?php

function updateActionAndhraPradesh()
{
    $id_election = 30;
    $id_state = 7;
    $ST_CODE = 28;
    
    $stateobj = State::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://aplegislature.org/web/legislative-assembly/member-s-information' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( Yii::app ()->basePath . '/../docs/andhrapradesh/mlas.html' ) );
        
        // since its the only table
        $xpath = new DOMXpath ( $doc );
        $TRs = $xpath->query ( "//div[@class='data']" );
        
        if ($TRs->length == 0)
            die ( 'Assembly parsing failed. TRs not found' );
        $rctr = 0;
        foreach ( $TRs as $tr )
        {
            $row = [ ];
            $imgs = $tr->getElementsByTagName('img');
            if($imgs->length !== 1)
                die("Not found the img");
            
            $img = $imgs->item(0)->getAttribute ( 'src' );
            
            $tds = $tr->childNodes;
            $col = 0;
            $phones = null;
            $acobj = null;
            
            foreach ( $tds as $td )
            {
                echo "$col\t" . $td->nodeValue . "\n";
                switch ($col++)
                {
                    case 5 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    case 7 : // constituency
                        {
                            $mats = [ ];
                            if (! preg_match ( '/(?<acno>\d+)/', $td->nodeValue, $mats ))
                                die ( "No match for [" . $td . ']' );
                                                        
                            $acno = intval ( $mats ['acno'] );
                                                        
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    $attr = [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            if (! $acobj)
                                die ( '>> Could not find assembly ' . "#$acno\n" . print_r ( $attr, true ) );
                            break;
                        }
                    case 9 : // PARTY
                        $party = trim ( str_replace ( ':', '', $td->nodeValue) );
                        break;
                } // switch
            } // foreach TDs
            /*
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
            */
            $picture_path = null;
            
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
