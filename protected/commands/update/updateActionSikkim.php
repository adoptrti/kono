<?php

function updateActionSikkim()
{
    $id_election = 23;
    $ST_CODE = 11;
    $id_state = 31;
    
    $stateobj = States::model ()->findByPk ( $id_state );
    $eleobj = Election::model ()->findByPk ( $id_election );
    
    libxml_use_internal_errors ( true );
    
    $urls = [ 
            'http://www.sikkimassembly.org.in/mla.html',
            'http://www.sikkimassembly.org.in/mla1.html',
            'http://www.sikkimassembly.org.in/mla2.html',
            'http://www.sikkimassembly.org.in/mla3.html' 
    ];
    foreach ( $urls as $url )
    {
        echo "\n\nURL: $url\n";
        $doc = new DOMDocument ();
        $doc->loadHTML ( file_get_contents ( $url ) );
        $div = $doc->getElementById ( 'middle_col' );
        if (empty ( $div ))
            die ( 'Assembly parsing failed. middle_col not found' );
        
        $TRs = $div->getElementsByTagName ( 'tr' );
        
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
            foreach ( $tds as $td )
            {
                switch ($col ++)
                {
                    case 0 : // constituency
                        {
                            if (preg_match ( '/Next/', $td->nodeValue ))
                                continue;
                            
                            $mats = [ ];
                            if (! preg_match ( '/^(?<acno>\d+)\.(?<acname>.*)$/', $td->nodeValue, $mats ))
                            {
                                echo 'Not parsed constituency:[' . $td->nodeValue . "]\n";
                                $col = 10;
                                break;
                            }
                            $acno = $mats ['acno'];
                            echo "Got $acno >>\n";
                            $acname = $mats ['acname'];
                            $acobj = Constituency::model ()->findByAttributes ( 
                                    [ 
                                            'id_state' => $id_state,
                                            'ctype' => 'AMLY',
                                            'eci_ref' => $acno 
                                    ] );
                            if (! $acobj)
                                die ( 'Could not find assembly ' . $acno );
                            break;
                        }
                    case 1 : // member nane
                        $name = trim ( $td->nodeValue );
                        break;
                    case 2 : // picture
                        $imgs = $td->getElementsByTagName ( 'img' );
                        if ($imgs->length < 1)
                            die ( 'Not found img in ' . $acno );
                        $img = 'http://www.sikkimassembly.org.in/' . implode ( '/', 
                                array_reduce ( explode ( '/', $imgs->item ( 0 )->getAttribute ( 'src' ) ), 
                                        function ($rt, $item)
                                        {
                                            $rt [] = urlencode ( $item );
                                            return $rt;
                                        } ) );
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
                        break;
                } // switch
            } // foreach TDs
            
            $MLA = TamilNaduResults2016::model ()->findByAttributes ( 
                    [ 
                            'ST_CODE' => $ST_CODE,
                            'id_election' => $id_election,
                            'acno' => $acno 
                    ] );
            if (! $MLA)
                $MLA = new TamilNaduResults2016 ();
            
            $MLA->id_election = $id_election;
            $MLA->acname = $acname;
            $MLA->acno = $acno;
            $MLA->name = $name;
            $MLA->party = null;
            $MLA->phones = null;
            $MLA->address = null;
            $MLA->emails = null;
            $MLA->picture = $picture_path;
            $MLA->id_consti = $acobj->id_consti;
            $MLA->id_state = $acobj->id_state;
            $MLA->ST_CODE = $ST_CODE;
            
            if (! $MLA->save ())
            {
                print_r ( $MLA->errors );
                die ( 'Saving MLA failed for ' . $acno );
            }
        } // foreach TRs
    } // foreach URLs
}